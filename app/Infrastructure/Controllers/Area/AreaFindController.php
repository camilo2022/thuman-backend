<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Area;

use App\Application\AreaUseCase\AreaFindUseCase;
use App\Domain\Area\Values\AreaId;
use App\Http\Resources\Area\AreaResource;
use App\Infrastructure\Repositories\AreaRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class AreaFindController
{
    use ApiResponser;
    use ApiMessage;
    private $areaRepository;

    public function __construct(AreaRepository $areaRepository)
    {
        $this->areaRepository = $areaRepository;
    }

    /**
     * @OA\Get(
     *     path="/Areas/Find/{areaId}",
     *     summary="Obtener un área por ID",
     *     description="Retorna la información de un área (incluye registros eliminados lógicamente).",
     *     tags={"Areas"},
     *     @OA\Parameter(
     *         name="areaId",
     *         in="path",
     *         required=true,
     *         description="ID del área a consultar",
     *         @OA\Schema(type="integer", example=7)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="El área fue encontrada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="area", type="object",
     *                     @OA\Property(property="id", type="integer", example=7),
     *                     @OA\Property(property="name", type="string", example="Tecnología"),
     *                     @OA\Property(property="description", type="string", example="Área encargada de TI"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-02 20:00:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-07-02 21:00:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="El area fue encontrada exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=200)
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
     *         description="Error inesperado al buscar el área.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error al buscar el area: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(int $areaId)
    {
        try {
            $areaId = new AreaId($areaId ?? null);
            $areaFindUseCase = new AreaFindUseCase($this->areaRepository);
            $area = new AreaResource($areaFindUseCase->__invoke($areaId));
            return $this->successResponse(
                $area,
                'El area fue encontrada exitosamente.',
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
