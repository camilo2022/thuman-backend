<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\EntityType;

use App\Application\EntityTypeUseCase\EntityTypeDeleteUseCase;
use App\Domain\EntityType\Values\EntityTypeId;
use App\Http\Resources\EntityType\EntityTypeResource;
use App\Infrastructure\Repositories\EntityTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class EntityTypeDeleteController
{
    use ApiResponser;
    use ApiMessage;
    private $entityTypeRepository;

    public function __construct(EntityTypeRepository $entityTypeRepository)
    {
        $this->entityTypeRepository = $entityTypeRepository;
    }

    /**
     * @OA\Delete(
     *     path="/EntityTypes/Delete/{entityTypeId}",
     *     summary="Eliminar tipo de entidad",
     *     description="Elimina lógicamente un tipo de entidad por su ID (item_id = 34).",
     *     tags={"EntityTypes"},
     *     @OA\Parameter(
     *         name="entityTypeId",
     *         in="path",
     *         required=true,
     *         description="ID del tipo de entidad a eliminar",
     *         @OA\Schema(type="integer", example=7)
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="El tipo de entidad fue eliminada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="entityType", type="object",
     *                     @OA\Property(property="id", type="integer", example=7),
     *                     @OA\Property(property="item_id", type="integer", example=34),
     *                     @OA\Property(property="name", type="string", example="Sociedad Limitada"),
     *                     @OA\Property(property="description", type="string", example="Entidad societaria tipo LTDA."),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-18 09:30:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-18 10:15:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", example="2025-08-18 10:20:00")
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="El tipo de entidad fue eliminada exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=204)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tipo de entidad no encontrado.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="El tipo de entidad no existe."),
     *             @OA\Property(property="error", type="string", example="El tipo de entidad no existe: No se encontró el ID especificado."),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al eliminar el tipo de entidad.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ocurrió un error."),
     *             @OA\Property(property="error", type="string", example="Error al eliminar el tipo de entidad: Error en la base de datos."),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */
    public function __invoke(int $entityTypeId)
    {
        try {
            $entityTypeId = new EntityTypeId($entityTypeId);
            $entityTypeDeleteUseCase = new EntityTypeDeleteUseCase($this->entityTypeRepository);
            $entityType = new EntityTypeResource($entityTypeDeleteUseCase->__invoke($entityTypeId));
            return $this->successResponse(
                $entityType,
                'El tipo de entidad fue inactivada exitosamente.',
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
