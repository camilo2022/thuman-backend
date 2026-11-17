<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Enterprise\Entity;

use App\Application\EnterpriseUseCase\EntityUseCase\EntityFindUseCase;
use App\Domain\Enterprise\Entity\Values\EntityId;
use App\Http\Resources\Enterprise\Entity\EntityResource;
use App\Infrastructure\Repositories\EntityRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

final class EntityFindController
{
    use ApiResponser;
    use ApiMessage;
    private $entityRepository;

    public function __construct(EntityRepository $entityRepository)
    {
        $this->entityRepository = $entityRepository;
    }

    /**
     * @OA\Get(
     *     path="/Enterprises/Entities/Find/{entityId}",
     *     summary="Encontrar entidad",
     *     description="Obtiene la información detallada de una entidad específica por su ID.",
     *     tags={"Enterprises - Entities"},
     *     @OA\Parameter(
     *         name="entityId",
     *         in="path",
     *         required=true,
     *         description="ID de la entidad",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Entidad encontrada exitosamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="entity",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="enterprise_id", type="integer", example=10),
     *                 @OA\Property(property="company_type_id", type="integer", example=5),
     *                 @OA\Property(property="name", type="string", example="Entidad ejemplo"),
     *                 @OA\Property(property="country_id", type="integer", example=57),
     *                 @OA\Property(property="document_type_id", type="integer", example=2),
     *                 @OA\Property(property="document_number", type="string", example="900123456"),
     *                 @OA\Property(property="person_id", type="integer", example=15),
     *                 @OA\Property(property="about_us", type="string", example="Texto sobre la empresa"),
     *                 @OA\Property(property="mission", type="string", example="Nuestra misión"),
     *                 @OA\Property(property="vision", type="string", example="Nuestra visión"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-18 10:00:00"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-18 12:00:00"),
     *                 @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="La entidad no existe"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al buscar la entidad"
     *     )
     * )
     */
    public function __invoke(int $entityId)
    {
        Gate::authorize('findEntity');
        
        try {
            $entityId = new EntityId($entityId ?? null);
            $entityFindUseCase = new EntityFindUseCase($this->entityRepository);
            $entity = new EntityResource($entityFindUseCase->__invoke($entityId));
            return $this->successResponse(
                $entity,
                'La entidad fue encontrada exitosamente.',
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
