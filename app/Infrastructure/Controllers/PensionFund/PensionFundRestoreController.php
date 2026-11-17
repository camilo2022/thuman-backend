<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\PensionFund;

use App\Application\PensionFundUseCase\PensionFundRestoreUseCase;
use App\Domain\PensionFund\Values\PensionFundId;
use App\Http\Resources\PensionFund\PensionFundResource;
use App\Infrastructure\Repositories\PensionFundRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class PensionFundRestoreController
{
    use ApiResponser;
    use ApiMessage;
    private $pensionFundRepository;

    public function __construct(PensionFundRepository $pensionFundRepository)
    {
        $this->pensionFundRepository = $pensionFundRepository;
    }

    /**
     * @OA\Patch(
     *     path="/PensionFunds/Restore/{pensionFundId}",
     *     summary="Restaurar fondo de pensión",
     *     description="Restaura lógicamente un fondo de pensión por su ID.",
     *     tags={"PensionFunds"},
     *     @OA\Parameter(
     *         name="pensionFundId",
     *         in="path",
     *         required=true,
     *         description="ID del fondo de pensión a restaurar",
     *         @OA\Schema(type="integer", example=12)
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="El fondo de pensión fue restaurada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="pensionFund", type="object",
     *                     @OA\Property(property="id", type="integer", example=12),
     *                     @OA\Property(property="item_id", type="integer", example=31),
     *                     @OA\Property(property="name", type="string", example="Protección Pensiones"),
     *                     @OA\Property(property="description", type="string", example="Fondo de pensión — régimen obligatorio"),
     *                     @OA\Property(property="settings", type="object", example={"visible":true}),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-18 09:30:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-18 10:15:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="El fondo de pensión fue restaurada exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=204)
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
     *         response=500,
     *         description="Error inesperado al restaurar el fondo de pensión.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ocurrió un error."),
     *             @OA\Property(property="error", type="string", example="Error al eliminar el fondo de pensión: Error en la base de datos."),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */
    public function __invoke(int $pensionFundId)
    {
        try {
            $pensionFundId = new PensionFundId($pensionFundId);
            $pensionFundRestoreUseCase = new PensionFundRestoreUseCase($this->pensionFundRepository);
            $pensionFund = new PensionFundResource($pensionFundRestoreUseCase->__invoke($pensionFundId));
            return $this->successResponse(
                $pensionFund,
                'El fondo de pensión fue activada exitosamente.',
                204
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
