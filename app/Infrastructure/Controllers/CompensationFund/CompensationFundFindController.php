<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\CompensationFund;

use App\Application\CompensationFundUseCase\CompensationFundFindUseCase;
use App\Domain\CompensationFund\Values\CompensationFundId;
use App\Http\Resources\CompensationFund\CompensationFundResource;
use App\Infrastructure\Repositories\CompensationFundRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class CompensationFundFindController
{
    use ApiResponser;
    use ApiMessage;
    private $compensationFundRepository;

    public function __construct(CompensationFundRepository $compensationFundRepository)
    {
        $this->compensationFundRepository = $compensationFundRepository;
    }

    /**
     * @OA\Get(
     *     path="/CompensationFunds/Find/{compensationFundId}",
     *     summary="Buscar caja de compensación por ID",
     *     description="Obtiene la información de una caja de compensación específica (incluye eliminadas lógicamente).",
     *     tags={"CompensationFunds"},
     *     @OA\Parameter(
     *         name="compensationFundId",
     *         in="path",
     *         required=true,
     *         description="ID de la caja de compensación",
     *         @OA\Schema(type="integer", example=5)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="La caja de compensación fue encontrada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="compensationFund", type="object",
     *                     @OA\Property(property="id", type="integer", example=5),
     *                     @OA\Property(property="item_id", type="integer", example=30),
     *                     @OA\Property(property="name", type="string", example="Comfama"),
     *                     @OA\Property(property="description", type="string", example="Caja de compensación familiar de Antioquia."),
     *                     @OA\Property(property="settings", type="object", example={"code":"CFA","country_id":48}),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-03-01 10:00:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-03-05 16:00:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="La caja de compensación fue encontrada exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Caja de compensación no encontrada.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="La caja de compensación no existe."),
     *             @OA\Property(property="error", type="string", example="No query results for model [CompensationFund] 99")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al buscar la caja de compensación.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se ha producido un error."),
     *             @OA\Property(property="error", type="string", example="Error al buscar la caja de compensación: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(int $compensationFundId)
    {
        try {
            $compensationFundId = new CompensationFundId($compensationFundId ?? null);
            $compensationFundFindUseCase = new CompensationFundFindUseCase($this->compensationFundRepository);
            $compensationFund = new CompensationFundResource($compensationFundFindUseCase->__invoke($compensationFundId));
            return $this->successResponse(
                $compensationFund,
                'La caja de compensación fue encontrada exitosamente.',
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
