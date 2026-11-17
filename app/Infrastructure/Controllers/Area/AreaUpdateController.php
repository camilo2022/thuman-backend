<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Area;

use App\Application\AreaUseCase\AreaUpdateUseCase;
use App\Domain\Area\Values\AreaId;
use App\Http\Requests\Area\AreaUpdateRequest;
use App\Http\Resources\Area\AreaResource;
use App\Infrastructure\Repositories\AreaRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class AreaUpdateController
{
    use ApiResponser;
    use ApiMessage;
    private $areaRepository;

    public function __construct(AreaRepository $areaRepository)
    {
        $this->areaRepository = $areaRepository;
    }

    /**
     * @OA\Put(
     *     path="/Areas/Update/{areaId}",
     *     summary="Actualizar un área",
     *     description="Actualiza un área por su ID.",
     *     tags={"Areas"},
     *     @OA\Parameter(
     *         name="areaId",
     *         in="path",
     *         required=true,
     *         description="ID del área a actualizar",
     *         @OA\Schema(type="integer", example=21)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","description"},
     *             @OA\Property(property="name", type="string", maxLength=80, example="Tecnología"),
     *             @OA\Property(property="description", type="string", maxLength=255, example="Área encargada de TI y soporte.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="El area fue actualizado exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="area", type="object",
     *                     @OA\Property(property="id", type="integer", example=21),
     *                     @OA\Property(property="name", type="string", example="Tecnología"),
     *                     @OA\Property(property="description", type="string", example="Área encargada de TI y soporte."),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-02 20:00:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-07-03 09:30:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="El area fue actualizado exitosamente."),
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
     *         response=422,
     *         description="Error de validación.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error de validación."),
     *             @OA\Property(property="attributes", type="object",
     *                 @OA\Property(property="name", type="string", example="Nombre"),
     *                 @OA\Property(property="description", type="string", example="Descripción")
     *             ),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="name", type="array",
     *                     @OA\Items(type="string", example="Ya ha sido tomado.")
     *                 ),
     *                 @OA\Property(property="description", type="array",
     *                     @OA\Items(type="string", example="No debe exceder los 255 caracteres.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al actualizar el área.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error al actualizar el area: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(AreaUpdateRequest $request, int $areaId)
    {
        try {
            $areaId = new AreaId($areaId ?? null);
            $areaUpdateUseCase = new AreaUpdateUseCase($this->areaRepository);
            $area = new AreaResource($areaUpdateUseCase->__invoke($request, $areaId));
            return $this->successResponse(
                $area,
                'El área fue actualizada exitosamente.',
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
