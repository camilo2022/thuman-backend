<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Benefit;

use App\Application\BenefitUseCase\BenefitStoreUseCase;
use App\Http\Requests\Benefit\BenefitStoreRequest;
use App\Http\Resources\Benefit\BenefitResource;
use App\Infrastructure\Repositories\BenefitRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class BenefitStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $benefitRepository;

    public function __construct(BenefitRepository $benefitRepository)
    {
        $this->benefitRepository = $benefitRepository;
    }

    /**
     * @OA\Post(
     *     path="/Benefits/Store",
     *     summary="Registrar un nuevo beneficio",
     *     description="Crea un nuevo beneficio con los datos proporcionados.",
     *     tags={"Benefits"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"item_id","name","description"},
     *             @OA\Property(property="item_id", type="integer", example=21, description="Debe ser 21 (categorización fija)"),
     *             @OA\Property(property="name", type="string", maxLength=80, example="Auxilio de transporte"),
     *             @OA\Property(property="description", type="string", maxLength=255, example="Subsidio mensual de transporte.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="El beneficio registrado exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="benefit", type="object",
     *                     @OA\Property(property="id", type="integer", example=9),
     *                     @OA\Property(property="name", type="string", example="Auxilio de transporte"),
     *                     @OA\Property(property="description", type="string", example="Subsidio mensual de transporte."),
     *                     @OA\Property(property="settings", type="object", example={"order":1}),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-03-01 10:15:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-03-01 10:15:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="El beneficio registrado exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=201)
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error de validación."),
     *             @OA\Property(property="attributes", type="object",
     *                 @OA\Property(property="item_id", type="string", example="Categorización"),
     *                 @OA\Property(property="name", type="string", example="Nombre"),
     *                 @OA\Property(property="description", type="string", example="Descripción")
     *             ),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="item_id", type="array", @OA\Items(type="string", example="No existe en la base de datos.")),
     *                 @OA\Property(property="name", type="array", @OA\Items(type="string", example="Ya ha sido tomado.")),
     *                 @OA\Property(property="description", type="array", @OA\Items(type="string", example="No debe exceder los 255 caracteres."))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al guardar el beneficio.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se ha producido un error."),
     *             @OA\Property(property="error", type="string", example="Error al guardar el beneficio: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(BenefitStoreRequest $request)
    {
        try {
            $benefitStoreUseCase = new BenefitStoreUseCase($this->benefitRepository);
            $benefit = new BenefitResource($benefitStoreUseCase->__invoke($request));
            return $this->successResponse(
                $benefit,
                'El beneficio fue registrado exitosamente.',
                201
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('Exception'),
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }
}
