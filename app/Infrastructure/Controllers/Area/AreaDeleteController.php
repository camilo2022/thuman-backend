<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Area;

use App\Application\AreaUseCase\AreaDeleteUseCase;
use App\Domain\Area\Values\AreaId;
use App\Http\Resources\Area\AreaResource;
use App\Infrastructure\Repositories\AreaRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class AreaDeleteController
{
    use ApiResponser;
    use ApiMessage;
    private $areaRepository;

    public function __construct(AreaRepository $areaRepository)
    {
        $this->areaRepository = $areaRepository;
    }

    /**
     * @OA\Delete(
     *     path="/Areas/Delete/{areaId}",
     *     summary="Eliminar un área",
     *     description="Elimina un área por su ID.",
     *     tags={"Areas"},
     *     @OA\Parameter(
     *         name="areaId",
     *         in="path",
     *         required=true,
     *         description="ID del área a eliminar",
     *         @OA\Schema(type="integer", example=21)
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="La habilidad fue eliminada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="La habilidad fue eliminada exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=204)
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
     *         description="Error inesperado al eliminar el área.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error al eliminar el area: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(int $areaId)
    {
        try {
            $areaId = new AreaId($areaId);
            $areaDeleteUseCase = new AreaDeleteUseCase($this->areaRepository);
            $area = new AreaResource($areaDeleteUseCase->__invoke($areaId));
            return $this->successResponse(
                $area,
                'El área fue inactivada exitosamente.',
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
                    'error' => $e->getMessage(),
                    'status' => 500
                ],
                500
            );
        }
    }
}
