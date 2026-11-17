<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\EntityType;

use App\Application\EntityTypeUseCase\EntityTypeFindUseCase;
use App\Domain\EntityType\Values\EntityTypeId;
use App\Http\Resources\EntityType\EntityTypeResource;
use App\Infrastructure\Repositories\EntityTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class EntityTypeFindController
{
    use ApiResponser;
    use ApiMessage;
    private $entityTypeRepository;

    public function __construct(EntityTypeRepository $entityTypeRepository)
    {
        $this->entityTypeRepository = $entityTypeRepository;
    }

    /**
     * @OA\Get(
     *     path="/EntityTypes/Find/{entityTypeId}",
     *     summary="Consultar tipo de entidad por ID",
     *     description="Obtiene el detalle de un tipo de entidad (item_id = 34) por su identificador único.",
     *     tags={"EntityTypes"},
     *     @OA\Parameter(
     *         name="entityTypeId",
     *         in="path",
     *         required=true,
     *         description="ID del tipo de entidad",
     *         @OA\Schema(type="integer", example=3)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="El tipo de entidad fue encontrada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="entityType", type="object",
     *                     @OA\Property(property="id", type="integer", example=3),
     *                     @OA\Property(property="item_id", type="integer", example=34),
     *                     @OA\Property(property="name", type="string", example="Cooperativa"),
     *                     @OA\Property(property="description", type="string", example="Entidad de tipo cooperativa"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-18 09:30:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-18 10:15:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="El tipo de entidad fue encontrada exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="El tipo de entidad no existe.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="El tipo de entidad no existe."),
     *             @OA\Property(property="error", type="string", example="No se encontró el tipo de entidad con el ID especificado."),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al buscar el tipo de entidad.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ocurrió un error."),
     *             @OA\Property(property="error", type="string", example="Error al buscar el tipo de entidad: Error en la base de datos."),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */
    public function __invoke(int $entityTypeId)
    {
        try {
            $entityTypeId = new EntityTypeId($entityTypeId ?? null);
            $entityTypeFindUseCase = new EntityTypeFindUseCase($this->entityTypeRepository);
            $entityType = new EntityTypeResource($entityTypeFindUseCase->__invoke($entityTypeId));
            return $this->successResponse(
                $entityType,
                'El tipo de entidad fue encontrada exitosamente.',
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
