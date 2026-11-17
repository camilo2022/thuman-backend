<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\CompensationFund;

use App\Application\CompensationFundUseCase\CompensationFundDeleteUseCase;
use App\Domain\CompensationFund\Values\CompensationFundId;
use App\Http\Resources\CompensationFund\CompensationFundResource;
use App\Infrastructure\Repositories\CompensationFundRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class CompensationFundDeleteController
{
    use ApiResponser;
    use ApiMessage;
    private $compensationFundRepository;

    public function __construct(CompensationFundRepository $compensationFundRepository)
    {
        $this->compensationFundRepository = $compensationFundRepository;
    }

    /**
     * @OA\Delete(
     *     path="/CompensationFunds/Delete/{compensationFundId}",
     *     summary="Eliminar caja de compensación",
     *     description="Elimina lógicamente una caja de compensación por su ID.",
     *     tags={"CompensationFunds"},
     *     @OA\Parameter(
     *         name="compensationFundId",
     *         in="path",
     *         required=true,
     *         description="ID de la caja de compensación a eliminar",
     *         @OA\Schema(type="integer", example=9)
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="La caja de compensación fue eliminada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="compensationFund", type="object",
     *                     @OA\Property(property="id", type="integer", example=9),
     *                     @OA\Property(property="item_id", type="integer", example=30),
     *                     @OA\Property(property="name", type="string", example="Caja Compensar"),
     *                     @OA\Property(property="description", type="string", example="Caja de compensación familiar."),
     *                     @OA\Property(property="settings", type="object", example={"visible":true}),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-18 10:00:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-18 11:00:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", example="2025-08-18 11:30:00")
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="La caja de compensación fue eliminada exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=204)
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
     *         description="Error inesperado al eliminar la caja de compensación.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ocurrió un error."),
     *             @OA\Property(property="error", type="string", example="Error al eliminar la caja de compensación: Error en la base de datos."),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */
    public function __invoke(int $compensationFundId)
    {
        try {
            $compensationFundId = new CompensationFundId($compensationFundId);
            $compensationFundDeleteUseCase = new CompensationFundDeleteUseCase($this->compensationFundRepository);
            $compensationFund = new CompensationFundResource($compensationFundDeleteUseCase->__invoke($compensationFundId));
            return $this->successResponse(
                $compensationFund,
                'La caja de compensación fue inactivada exitosamente.',
                204
            );
        } catch (HttpException $e) {
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('Exception'),
                    'error' => $e->getMessage(),
                    'status' => $e->getStatusCode()
                ],
                $e->getStatusCode()
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
