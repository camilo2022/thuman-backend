<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\RiskManager;

use App\Application\RiskManagerUseCase\RiskManagerRestoreUseCase;
use App\Domain\RiskManager\Values\RiskManagerId;
use App\Http\Resources\RiskManager\RiskManagerResource;
use App\Infrastructure\Repositories\RiskManagerRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class RiskManagerRestoreController
{
    use ApiResponser;
    use ApiMessage;
    private $riskManagerRepository;

    public function __construct(RiskManagerRepository $riskManagerRepository)
    {
        $this->riskManagerRepository = $riskManagerRepository;
    }

    /**
     * @OA\Patch(
     *     path="/RiskManagers/Restore/{riskManagerId}",
     *     summary="Restaurar administradora de riesgo laboral",
     *     description="Restaura (revierte el soft delete) una administradora de riesgo laboral.",
     *     tags={"RiskManagers"},
     *     @OA\Parameter(
     *         name="riskManagerId",
     *         in="path",
     *         required=true,
     *         description="ID de la administradora de riesgo laboral a restaurar",
     *         @OA\Schema(type="integer", example=7)
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="La administradora de riesgo laboral fue restaurada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="riskManager", type="object",
     *                     @OA\Property(property="id", type="integer", example=7),
     *                     @OA\Property(property="item_id", type="integer", example=27),
     *                     @OA\Property(property="name", type="string", example="ARL Sura"),
     *                     @OA\Property(property="description", type="string", example="Administradora de Riesgos Laborales Sura."),
     *                     @OA\Property(property="settings", type="object", example={"nit":"890.123.456-7"}),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-10 09:30:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-02-15 12:45:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="La administradora de riesgo laboral fue restaurada exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=204)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Administradora de riesgo laboral no encontrada.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se ha producido un error."),
     *             @OA\Property(property="error", type="string", example="La administradora de riesgo laboral no existe: No se encontró el ID especificado.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al restaurar la administradora de riesgo laboral.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se ha producido un error."),
     *             @OA\Property(property="error", type="string", example="Error al eliminar la administradora de riesgo laboral: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(int $riskManagerId)
    {
        try {
            $riskManagerId = new RiskManagerId($riskManagerId);
            $riskManagerRestoreUseCase = new RiskManagerRestoreUseCase($this->riskManagerRepository);
            $riskManager = new RiskManagerResource($riskManagerRestoreUseCase->__invoke($riskManagerId));
            return $this->successResponse(
                $riskManager,
                'La administradora de riesgo laboral fue activada exitosamente.',
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
