<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Enterprise\Entity;

use App\Application\EnterpriseUseCase\EntityUseCase\EntityProfileUseCase;
use App\Domain\Enterprise\Entity\Values\EntityId;
use App\Http\Requests\Enterprise\Entity\EntityProfileRequest;
use App\Http\Resources\Enterprise\Entity\EntityResource;
use App\Infrastructure\Repositories\EntityRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

final class EntityProfileController
{
    use ApiResponser;
    use ApiMessage;
    private $entityRepository;

    public function __construct(EntityRepository $entityRepository)
    {
        $this->entityRepository = $entityRepository;
    }

    /**
     * @OA\Put(
     *     path="/Enterprises/Entities/Profile/{entityId}",
     *     summary="Actualizar perfil de la entidad empresa",
     *     description="Actualiza la información del perfil (Sobre nosotros, Misión y Visión) de una entidad empresarial.",
     *     tags={"Enterprises - Entities"},
     *     @OA\Parameter(
     *         name="entityId",
     *         in="path",
     *         required=true,
     *         description="ID de la entidad empresa a actualizar",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"about_us","mission","vision"},
     *             @OA\Property(property="about_us", type="string", maxLength=500, example="Somos una empresa dedicada a la innovación tecnológica."),
     *             @OA\Property(property="mission", type="string", maxLength=500, example="Proveer soluciones digitales que mejoren la vida de las personas."),
     *             @OA\Property(property="vision", type="string", maxLength=500, example="Ser líderes en transformación digital a nivel global.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Perfil de la entidad actualizado exitosamente",
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
     *                 @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Entidad no encontrada"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al actualizar el perfil"
     *     )
     * )
     */
    public function __invoke(EntityProfileRequest $request, int $entityId)
    {
        Gate::authorize('profileEntity');
        
        try {
            $entityId = new EntityId($entityId ?? null);
            $entityProfileUseCase = new EntityProfileUseCase($this->entityRepository);
            $entity = new EntityResource($entityProfileUseCase->__invoke($request, $entityId));
            return $this->successResponse(
                $entity,
                'El perfil de la entidad empresa fue actualizado exitosamente.',
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
