<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\PensionFund;

use App\Application\PensionFundUseCase\PensionFundUpdateUseCase;
use App\Domain\PensionFund\Values\PensionFundId;
use App\Http\Requests\PensionFund\PensionFundUpdateRequest;
use App\Http\Resources\PensionFund\PensionFundResource;
use App\Infrastructure\Repositories\PensionFundRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class PensionFundUpdateController
{
    use ApiResponser;
    use ApiMessage;
    private $pensionFundRepository;

    public function __construct(PensionFundRepository $pensionFundRepository)
    {
        $this->pensionFundRepository = $pensionFundRepository;
    }

    /**
     * @OA\Put(
     *     path="/PensionFunds/Update/{pensionFundId}",
     *     summary="Actualizar fondo de pensión",
     *     description="Actualiza un fondo de pensión por su ID.",
     *     tags={"PensionFunds"},
     *     @OA\Parameter(
     *         name="pensionFundId",
     *         in="path",
     *         required=true,
     *         description="ID del fondo de pensión a actualizar",
     *         @OA\Schema(type="integer", example=12)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"item_id","name","description"},
     *             @OA\Property(property="item_id", type="integer", example=30, description="Categorización (debe ser 30 según validación)"),
     *             @OA\Property(property="name", type="string", maxLength=80, example="Protección Pensiones", description="Nombre del fondo de pensión"),
     *             @OA\Property(property="description", type="string", maxLength=255, example="Fondo de pensiones — régimen obligatorio", description="Descripción del fondo")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="El fondo de pensión fue actualizado exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="pensionFund", type="object",
     *                     @OA\Property(property="id", type="integer", example=12),
     *                     @OA\Property(property="item_id", type="integer", example=30),
     *                     @OA\Property(property="name", type="string", example="Protección Pensiones"),
     *                     @OA\Property(property="description", type="string", example="Fondo de pensiones — régimen obligatorio"),
     *                     @OA\Property(property="settings", type="object", example={"visible":true}),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-18 09:30:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-18 11:00:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="El fondo de pensión fue actualizado exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Fondo de pensión no encontrado.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ocurrió un error."),
     *             @OA\Property(property="error", type="string", example="El fondo de pensión no existe: No se encontró el ID especificado."),
     *             @OA\Property(property="status", type="integer", example=404)
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
     *             ),
     *             @OA\Property(property="status", type="integer", example=422)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al actualizar el fondo de pensión.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ocurrió un error."),
     *             @OA\Property(property="error", type="string", example="Error al actualizar el fondo de pensión: Error en la base de datos."),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */
    public function __invoke(PensionFundUpdateRequest $request, int $pensionFundId)
    {
        try {
            $pensionFundId = new PensionFundId($pensionFundId ?? null);
            $pensionFundUpdateUseCase = new PensionFundUpdateUseCase($this->pensionFundRepository);
            $pensionFund = new PensionFundResource($pensionFundUpdateUseCase->__invoke($request, $pensionFundId));
            return $this->successResponse(
                $pensionFund,
                'El fondo de pensión fue actualizado exitosamente.',
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
