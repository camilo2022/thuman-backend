<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\CompensationFund;

use App\Application\CompensationFundUseCase\CompensationFundUpdateUseCase;
use App\Domain\CompensationFund\Values\CompensationFundId;
use App\Http\Requests\CompensationFund\CompensationFundUpdateRequest;
use App\Http\Resources\CompensationFund\CompensationFundResource;
use App\Infrastructure\Repositories\CompensationFundRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class CompensationFundUpdateController
{
    use ApiResponser;
    use ApiMessage;
    private $compensationFundRepository;

    public function __construct(CompensationFundRepository $compensationFundRepository)
    {
        $this->compensationFundRepository = $compensationFundRepository;
    }

    /**
     * @OA\Put(
     *     path="/CompensationFunds/Update/{compensationFundId}",
     *     summary="Actualizar caja de compensación",
     *     description="Actualiza una caja de compensación por su ID.",
     *     tags={"CompensationFunds"},
     *     @OA\Parameter(
     *         name="compensationFundId",
     *         in="path",
     *         required=true,
     *         description="ID de la caja de compensación a actualizar",
     *         @OA\Schema(type="integer", example=7)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"item_id","name","description"},
     *             @OA\Property(property="item_id", type="integer", example=30, description="Categorización fija para cajas de compensación (debe ser 30)"),
     *             @OA\Property(property="name", type="string", maxLength=80, example="Caja Compensar", description="Nombre de la caja de compensación"),
     *             @OA\Property(property="description", type="string", maxLength=255, example="Caja de compensación familiar.", description="Descripción")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="La caja de compensación fue actualizada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="compensationFund", type="object",
     *                     @OA\Property(property="id", type="integer", example=7),
     *                     @OA\Property(property="item_id", type="integer", example=30),
     *                     @OA\Property(property="name", type="string", example="Caja Compensar"),
     *                     @OA\Property(property="description", type="string", example="Caja de compensación familiar."),
     *                     @OA\Property(property="settings", type="object", example={"visible":true}),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-18 10:00:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-18 12:00:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="La caja de compensación fue actualizada exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error de validación."),
     *             @OA\Property(property="attributes", type="object", example={"item_id":30,"name":"Caja Compensar","description":"Texto"}),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="item_id", type="array", @OA\Items(type="string", example="No existe en la base de datos.")),
     *                 @OA\Property(property="name", type="array", @OA\Items(type="string", example="Ya ha sido tomado.")),
     *                 @OA\Property(property="description", type="array", @OA\Items(type="string", example="No debe exceder los 255 caracteres."))
     *             ),
     *             @OA\Property(property="status", type="integer", example=422)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Caja de compensación no encontrada.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="La caja de compensación no existe: No se encontró el ID especificado."),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al actualizar la caja de compensación.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ocurrió un error."),
     *             @OA\Property(property="error", type="string", example="Error al actualizar la caja de compensación: Error en la base de datos."),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */
    public function __invoke(CompensationFundUpdateRequest $request, int $compensationFundId)
    {
        try {
            $compensationFundId = new CompensationFundId($compensationFundId ?? null);
            $compensationFundUpdateUseCase = new CompensationFundUpdateUseCase($this->compensationFundRepository);
            $compensationFund = new CompensationFundResource($compensationFundUpdateUseCase->__invoke($request, $compensationFundId));
            return $this->successResponse(
                $compensationFund,
                'La caja de compensación fue actualizado exitosamente.',
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
