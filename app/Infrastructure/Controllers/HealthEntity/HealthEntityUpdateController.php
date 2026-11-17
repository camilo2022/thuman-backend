<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\HealthEntity;

use App\Application\HealthEntityUseCase\HealthEntityUpdateUseCase;
use App\Domain\HealthEntity\Values\HealthEntityId;
use App\Http\Requests\HealthEntity\HealthEntityUpdateRequest;
use App\Http\Resources\HealthEntity\HealthEntityResource;
use App\Infrastructure\Repositories\HealthEntityRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class HealthEntityUpdateController
{
    use ApiResponser;
    use ApiMessage;
    private $healthEntityRepository;

    public function __construct(HealthEntityRepository $healthEntityRepository)
    {
        $this->healthEntityRepository = $healthEntityRepository;
    }

    /**
     * @OA\Put(
     *     path="/HealthEntities/Update/{healthEntityId}",
     *     summary="Actualizar entidad de salud",
     *     description="Actualiza una entidad de salud por su ID y retorna el recurso actualizado.",
     *     tags={"HealthEntities"},
     *     @OA\Parameter(
     *         name="healthEntityId",
     *         in="path",
     *         required=true,
     *         description="ID de la entidad de salud",
     *         @OA\Schema(type="integer", example=17)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"health_entity_type_id","item_id","name","description"},
     *             @OA\Property(property="health_entity_type_id", type="integer", example=2, description="ID del tipo de entidad de salud (debe existir en subitems con item_id=5)"),
     *             @OA\Property(property="item_id", type="integer", example=4, description="Debe ser 4 según validación"),
     *             @OA\Property(property="name", type="string", maxLength=80, example="EPS SURA"),
     *             @OA\Property(property="description", type="string", maxLength=255, example="Entidad promotora de salud del régimen contributivo.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="La entidad de salud fue actualizado exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="healthEntity", type="object",
     *                     @OA\Property(property="id", type="integer", example=17),
     *                     @OA\Property(property="item_id", type="integer", example=4),
     *                     @OA\Property(property="name", type="string", example="EPS SURA"),
     *                     @OA\Property(property="description", type="string", example="Entidad promotora de salud del régimen contributivo."),
     *                     @OA\Property(property="settings", type="object", example={"order":1}),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-10 09:30:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-02-15 12:45:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="La entidad de salud fue actualizado exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="La entidad de salud no existe.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se ha producido un error."),
     *             @OA\Property(property="error", type="string", example="La entidad de salud no existe: No se encontró el recurso.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error de validación."),
     *             @OA\Property(property="attributes", type="object",
     *                 @OA\Property(property="health_entity_type_id", type="string", example="Tipo de entidad de salud"),
     *                 @OA\Property(property="item_id", type="string", example="Categorización"),
     *                 @OA\Property(property="name", type="string", example="Nombre"),
     *                 @OA\Property(property="description", type="string", example="Descripción")
     *             ),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="health_entity_type_id", type="array", @OA\Items(type="string", example="No existe en la base de datos.")),
     *                 @OA\Property(property="item_id", type="array", @OA\Items(type="string", example="No existe en la base de datos.")),
     *                 @OA\Property(property="name", type="array", @OA\Items(type="string", example="Ya ha sido tomado.")),
     *                 @OA\Property(property="description", type="array", @OA\Items(type="string", example="No debe exceder los 255 caracteres."))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al actualizar la entidad de salud.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se ha producido un error."),
     *             @OA\Property(property="error", type="string", example="Error al actualizar la entidad de salud: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(HealthEntityUpdateRequest $request, int $healthEntityId)
    {
        try {
            $healthEntityId = new HealthEntityId($healthEntityId ?? null);
            $healthEntityUpdateUseCase = new HealthEntityUpdateUseCase($this->healthEntityRepository);
            $healthEntity = new HealthEntityResource($healthEntityUpdateUseCase->__invoke($request, $healthEntityId));
            return $this->successResponse(
                $healthEntity,
                'La entidad de salud fue actualizado exitosamente.',
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
