<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Area;

use App\Application\AreaUseCase\AreaRestoreUseCase;
use App\Domain\Area\Values\AreaId;
use App\Http\Resources\Area\AreaResource;
use App\Infrastructure\Repositories\AreaRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class AreaRestoreController
{
    use ApiResponser;
    use ApiMessage;
    private $areaRepository;

    public function __construct(AreaRepository $areaRepository)
    {
        $this->areaRepository = $areaRepository;
    }

    /**
     * @OA\Patch(
     *     path="/Areas/Restore/{areaId}",
     *     summary="Restaurar un área",
     *     description="Restaura un área previamente eliminada (soft delete) por su ID.",
     *     tags={"Areas"},
     *     @OA\Parameter(
     *         name="areaId",
     *         in="path",
     *         required=true,
     *         description="ID del área a restaurar",
     *         @OA\Schema(type="integer", example=21)
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="El área fue restaurada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="El area fue restaurada exitosamente."),
     *             @OA\Property(property="status", type="integer", example=204),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="area", type="object",
     *                     @OA\Property(property="id", type="integer", example=21),
     *                     @OA\Property(property="name", type="string", example="Área Administrativa"),
     *                     @OA\Property(property="description", type="string", example="Área encargada de la administración."),
     *                     @OA\Property(property="created_at", type="string", example="2025-08-10 10:45:00"),
     *                     @OA\Property(property="updated_at", type="string", example="2025-08-15 12:20:00"),
     *                     @OA\Property(property="deleted_at", type="string", nullable=true, example=null)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Área no encontrada.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="El area no existe: No se encontró el ID especificado.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al restaurar el área.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error al restaurar el area: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(int $areaId)
    {
        try {
            $areaId = new AreaId($areaId);
            $areaRestoreUseCase = new AreaRestoreUseCase($this->areaRepository);
            $area = new AreaResource($areaRestoreUseCase->__invoke($areaId));
            return $this->successResponse(
                $area,
                'El área fue activada exitosamente.',
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
