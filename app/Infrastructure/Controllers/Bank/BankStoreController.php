<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Bank;

use App\Application\BankUseCase\BankStoreUseCase;
use App\Http\Requests\Bank\BankStoreRequest;
use App\Http\Resources\Bank\BankResource;
use App\Infrastructure\Repositories\BankRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class BankStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $bankRepository;

    public function __construct(BankRepository $bankRepository)
    {
        $this->bankRepository = $bankRepository;
    }

    /**
     * @OA\Post(
     *     path="/Banks/Store",
     *     summary="Registrar un nuevo banco",
     *     description="Crea un nuevo banco con los datos proporcionados.",
     *     tags={"Banks"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"item_id","name","description"},
     *             @OA\Property(property="item_id", type="integer", example=14, description="Debe ser 14 (categorización fija)"),
     *             @OA\Property(property="name", type="string", maxLength=80, example="Banco Nacional"),
     *             @OA\Property(property="description", type="string", maxLength=255, example="Entidad financiera de cobertura nacional.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="El beneficio registrado exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="bank", type="object",
     *                     @OA\Property(property="id", type="integer", example=12),
     *                     @OA\Property(property="item_id", type="integer", example=14),
     *                     @OA\Property(property="name", type="string", example="Banco Nacional"),
     *                     @OA\Property(property="description", type="string", example="Entidad financiera de cobertura nacional."),
     *                     @OA\Property(property="settings", type="object", example={"swift":"BNACCOBB","country":"CO"}),
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
     *         description="Error inesperado al guardar el banco.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se ha producido un error."),
     *             @OA\Property(property="error", type="string", example="Error al guardar el banco: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(BankStoreRequest $request)
    {
        try {
            $bankStoreUseCase = new BankStoreUseCase($this->bankRepository);
            $bank = new BankResource($bankStoreUseCase->__invoke($request));
            return $this->successResponse(
                $bank,
                'El banco fue registrado exitosamente.',
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
