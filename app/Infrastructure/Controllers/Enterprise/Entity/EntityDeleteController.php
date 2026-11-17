<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Enterprise\Entity;

use App\Application\EnterpriseUseCase\EntityUseCase\EntityDeleteUseCase;
use App\Domain\Enterprise\Entity\Values\EntityId;
use App\Http\Resources\Enterprise\Entity\EntityResource;
use App\Infrastructure\Repositories\EntityRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

final class EntityDeleteController
{
    use ApiResponser;
    use ApiMessage;
    private $entityRepository;

    public function __construct(EntityRepository $entityRepository)
    {
        $this->entityRepository = $entityRepository;
    }

    /**
     * @OA\Delete(
     *     path="/Enterprises/Entities/Delete/{entityId}",
     *     summary="Eliminar entidad empresarial",
     *     description="Elimina lógicamente una entidad empresarial por su ID.",
     *     tags={"Enterprises - Entities"},
     *     @OA\Parameter(
     *         name="entityId",
     *         in="path",
     *         required=true,
     *         description="ID de la entidad a eliminar",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="La entidad fue eliminada exitosamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="entity",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="enterprise_id", type="integer", example=10),
     *                 @OA\Property(property="company_type_id", type="integer", example=3),
     *                 @OA\Property(property="name", type="string", example="Entidad Empresarial S.A.S."),
     *                 @OA\Property(property="country_id", type="integer", example=57),
     *                 @OA\Property(property="document_type_id", type="integer", example=4),
     *                 @OA\Property(property="document_number", type="string", example="901234567"),
     *                 @OA\Property(property="person_id", type="integer", example=20),
     *                 @OA\Property(property="about_us", type="string", example="Somos una empresa dedicada a la innovación tecnológica."),
     *                 @OA\Property(property="mission", type="string", example="Proveer soluciones digitales que mejoren la vida de las personas."),
     *                 @OA\Property(property="vision", type="string", example="Ser líderes en transformación digital a nivel global."),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-18 10:00:00"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-18 11:00:00"),
     *                 @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example="2025-08-18 11:30:00")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Entidad no encontrada"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al eliminar la entidad"
     *     )
     * )
     */
    public function __invoke(int $entityId)
    {
        Gate::authorize('deleteEntity');
        
        try {
            $entityId = new EntityId($entityId);
            $entityDeleteUseCase = new EntityDeleteUseCase($this->entityRepository);
            $entity = new EntityResource($entityDeleteUseCase->__invoke($entityId));
            return $this->successResponse(
                $entity,
                'La entidad fue inactivada exitosamente.',
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
