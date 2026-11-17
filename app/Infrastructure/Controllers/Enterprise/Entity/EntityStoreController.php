<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Enterprise\Entity;

use App\Application\EnterpriseUseCase\EntityUseCase\EntityStoreUseCase;
use App\Http\Requests\Enterprise\Entity\EntityStoreRequest;
use App\Http\Resources\Enterprise\Entity\EntityResource;
use App\Infrastructure\Repositories\EntityRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

final class EntityStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $entityRepository;

    public function __construct(EntityRepository $entityRepository)
    {
        $this->entityRepository = $entityRepository;
    }

    /**
     * @OA\Post(
     *     path="/Enterprises/Entities/Store",
     *     summary="Registrar entidad",
     *     description="Crea una nueva entidad asociada a una empresa.",
     *     tags={"Enterprises - Entities"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"enterprise_id","entity_type_id","name","country_id","document_type_id","document_number","person_id"},
     *             @OA\Property(property="enterprise_id", type="integer", example=10, description="ID de la empresa"),
     *             @OA\Property(property="entity_type_id", type="integer", example=2, description="ID del tipo de entidad (subitems, item_id=34)"),
     *             @OA\Property(property="name", type="string", maxLength=80, example="Entidad Ejemplo S.A.S."),
     *             @OA\Property(property="country_id", type="integer", example=57, description="ID del país"),
     *             @OA\Property(property="document_type_id", type="integer", example=4, description="ID del tipo de documento (subitems, item_id=4)"),
     *             @OA\Property(property="document_number", type="string", maxLength=30, example="900123456"),
     *             @OA\Property(property="person_id", type="integer", example=15, description="ID del representante legal (people.id)")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Entidad registrada exitosamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="entity",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="enterprise_id", type="integer", example=10),
     *                 @OA\Property(property="company_type_id", type="integer", example=3),
     *                 @OA\Property(property="name", type="string", example="Entidad Ejemplo S.A.S."),
     *                 @OA\Property(property="country_id", type="integer", example=57),
     *                 @OA\Property(property="document_type_id", type="integer", example=4),
     *                 @OA\Property(property="document_number", type="string", example="900123456"),
     *                 @OA\Property(property="person_id", type="integer", example=15),
     *                 @OA\Property(property="about_us", type="string", example=null),
     *                 @OA\Property(property="mission", type="string", example=null),
     *                 @OA\Property(property="vision", type="string", example=null),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-18 10:00:00"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-18 10:00:00"),
     *                 @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al guardar la entidad"
     *     )
     * )
     */
    public function __invoke(EntityStoreRequest $request)
    {
        Gate::authorize('storeEntity');
        
        try {
            $entityStoreUseCase = new EntityStoreUseCase($this->entityRepository);
            $entity = new EntityResource($entityStoreUseCase->__invoke($request));
            return $this->successResponse(
                $entity,
                'La entidad fue registrada exitosamente.',
                201
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
