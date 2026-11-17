<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\HealthEntity;

use App\Application\HealthEntityUseCase\HealthEntityStoreUseCase;
use App\Http\Requests\HealthEntity\HealthEntityStoreRequest;
use App\Http\Resources\HealthEntity\HealthEntityResource;
use App\Infrastructure\Repositories\HealthEntityRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class HealthEntityStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $healthEntityRepository;

    public function __construct(HealthEntityRepository $healthEntityRepository)
    {
        $this->healthEntityRepository = $healthEntityRepository;
    }

    /**
     * @OA\Post(
     *     path="/HealthEntities/Store",
     *     summary="Registrar entidad de salud",
     *     description="Crea una nueva entidad de salud y retorna el recurso creado.",
     *     tags={"HealthEntities"},
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
     *         response=201,
     *         description="La entidad de salud registrado exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="healthEntity", type="object",
     *                     @OA\Property(property="id", type="integer", example=17),
     *                     @OA\Property(property="item_id", type="integer", example=4),
     *                     @OA\Property(property="name", type="string", example="EPS SURA"),
     *                     @OA\Property(property="description", type="string", example="Entidad promotora de salud del régimen contributivo."),
     *                     @OA\Property(property="settings", type="object", example={"order":1}),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-10 09:30:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-01-10 09:30:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="La entidad de salud registrado exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=201)
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
     *         description="Error inesperado al guardar la entidad de salud.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se ha producido un error."),
     *             @OA\Property(property="error", type="string", example="Error al guardar la entidad de salud: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(HealthEntityStoreRequest $request)
    {
        try {
            $healthEntityStoreUseCase = new HealthEntityStoreUseCase($this->healthEntityRepository);
            $healthEntity = new HealthEntityResource($healthEntityStoreUseCase->__invoke($request));
            return $this->successResponse(
                $healthEntity,
                'La entidad de salud registrado exitosamente.',
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
