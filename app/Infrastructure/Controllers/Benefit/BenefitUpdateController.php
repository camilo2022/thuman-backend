<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Benefit;

use App\Application\BenefitUseCase\BenefitUpdateUseCase;
use App\Domain\Benefit\Values\BenefitId;
use App\Http\Requests\Benefit\BenefitUpdateRequest;
use App\Http\Resources\Benefit\BenefitResource;
use App\Infrastructure\Repositories\BenefitRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class BenefitUpdateController
{
    use ApiResponser;
    use ApiMessage;
    private $benefitRepository;

    public function __construct(BenefitRepository $benefitRepository)
    {
        $this->benefitRepository = $benefitRepository;
    }

    /**
     * @OA\Put(
     *     path="/Benefits/Update/{benefitId}",
     *     summary="Actualizar un beneficio",
     *     description="Actualiza un beneficio existente por su ID.",
     *     tags={"Benefits"},
     *     @OA\Parameter(
     *         name="benefitId",
     *         in="path",
     *         required=true,
     *         description="ID del beneficio a actualizar",
     *         @OA\Schema(type="integer", example=9)
     *     ),
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
     *         response=200,
     *         description="El beneficio fue actualizado exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="benefit", type="object",
     *                     @OA\Property(property="id", type="integer", example=9),
     *                     @OA\Property(property="name", type="string", example="Auxilio de transporte"),
     *                     @OA\Property(property="description", type="string", example="Subsidio mensual de transporte."),
     *                     @OA\Property(property="settings", type="object", example={"order":1}),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-10 09:30:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-03-02 14:10:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="El beneficio fue actualizado exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Beneficio no encontrado.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se ha producido un error."),
     *             @OA\Property(property="error", type="string", example="El beneficio no existe: No se encontró el ID especificado.")
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
     *         description="Error inesperado al actualizar el beneficio.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se ha producido un error."),
     *             @OA\Property(property="error", type="string", example="Error al actualizar el beneficio: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(BenefitUpdateRequest $request, int $benefitId)
    {
        try {
            $benefitId = new BenefitId($benefitId ?? null);
            $benefitUpdateUseCase = new BenefitUpdateUseCase($this->benefitRepository);
            $benefit = new BenefitResource($benefitUpdateUseCase->__invoke($request, $benefitId));
            return $this->successResponse(
                $benefit,
                'El beneficio fue actualizado exitosamente.',
                200
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
