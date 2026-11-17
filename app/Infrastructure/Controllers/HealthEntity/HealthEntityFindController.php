<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\HealthEntity;

use App\Application\HealthEntityUseCase\HealthEntityFindUseCase;
use App\Domain\HealthEntity\Values\HealthEntityId;
use App\Http\Resources\HealthEntity\HealthEntityResource;
use App\Infrastructure\Repositories\HealthEntityRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class HealthEntityFindController
{
    use ApiResponser;
    use ApiMessage;
    private $healthEntityRepository;

    public function __construct(HealthEntityRepository $healthEntityRepository)
    {
        $this->healthEntityRepository = $healthEntityRepository;
    }

    /**
     * @OA\Get(
     *     path="/HealthEntities/Find/{healthEntityId}",
     *     summary="Encontrar entidad de salud por ID",
     *     description="Retorna el detalle de una entidad de salud específica (incluye eliminados lógicamente).",
     *     tags={"HealthEntities"},
     *     @OA\Parameter(
     *         name="healthEntityId",
     *         in="path",
     *         required=true,
     *         description="ID de la entidad de salud a consultar",
     *         @OA\Schema(type="integer", example=7)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="La entidad de salud fue encontrada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="healthEntity", type="object",
     *                     @OA\Property(property="id", type="integer", example=7),
     *                     @OA\Property(property="item_id", type="integer", example=29),
     *                     @OA\Property(property="name", type="string", example="EPS SURA"),
     *                     @OA\Property(property="description", type="string", example="Entidad promotora de salud del régimen contributivo."),
     *                     @OA\Property(property="settings", type="object", example={"order":1}),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-10 09:30:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-02-15 12:45:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="La entidad de salud fue encontrada exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Entidad de salud no encontrada.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se ha producido un error."),
     *             @OA\Property(property="error", type="string", example="La entidad de salud no existe: No se encontró el ID especificado.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al buscar la entidad de salud.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se ha producido un error."),
     *             @OA\Property(property="error", type="string", example="Error al buscar la entidad de salud: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(int $healthEntityId)
    {
        try {
            $healthEntityId = new HealthEntityId($healthEntityId ?? null);
            $healthEntityFindUseCase = new HealthEntityFindUseCase($this->healthEntityRepository);
            $healthEntity = new HealthEntityResource($healthEntityFindUseCase->__invoke($healthEntityId));
            return $this->successResponse(
                $healthEntity,
                'La entidad de salud fue encontrada exitosamente.',
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
