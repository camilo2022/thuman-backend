<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\HealthEntityType;

use App\Application\HealthEntityTypeUseCase\HealthEntityTypeFindUseCase;
use App\Domain\HealthEntityType\Values\HealthEntityTypeId;
use App\Http\Resources\HealthEntityType\HealthEntityTypeResource;
use App\Infrastructure\Repositories\HealthEntityTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class HealthEntityTypeFindController
{
    use ApiResponser;
    use ApiMessage;
    private $healthEntityTypeRepository;

    public function __construct(HealthEntityTypeRepository $healthEntityTypeRepository)
    {
        $this->healthEntityTypeRepository = $healthEntityTypeRepository;
    }

    /**
     * @OA\Get(
     *     path="/HealthEntityTypes/Find/{healthEntityTypeId}",
     *     summary="Encontrar tipo de entidad de salud por ID",
     *     description="Retorna el detalle de un tipo de entidad de salud específico (incluye eliminados lógicamente).",
     *     tags={"HealthEntityTypes"},
     *     @OA\Parameter(
     *         name="healthEntityTypeId",
     *         in="path",
     *         required=true,
     *         description="ID del tipo de entidad de salud a consultar",
     *         @OA\Schema(type="integer", example=7)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="El tipo de entidad de salud fue encontrada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="healthEntityType", type="object",
     *                     @OA\Property(property="id", type="integer", example=7),
     *                     @OA\Property(property="item_id", type="integer", example=28),
     *                     @OA\Property(property="name", type="string", example="EPS"),
     *                     @OA\Property(property="description", type="string", example="Entidad Promotora de Salud."),
     *                     @OA\Property(property="settings", type="object", example={"order":1}),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-10 09:30:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-02-15 12:45:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="El tipo de entidad de salud fue encontrada exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tipo de entidad de salud no encontrado.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se ha producido un error."),
     *             @OA\Property(property="error", type="string", example="El tipo de entidad de salud no existe: No se encontró el ID especificado.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al buscar el tipo de entidad de salud.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se ha producido un error."),
     *             @OA\Property(property="error", type="string", example="Error al buscar el tipo de entidad de salud: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(int $healthEntityTypeId)
    {
        try {
            $healthEntityTypeId = new HealthEntityTypeId($healthEntityTypeId ?? null);
            $healthEntityTypeFindUseCase = new HealthEntityTypeFindUseCase($this->healthEntityTypeRepository);
            $healthEntityType = new HealthEntityTypeResource($healthEntityTypeFindUseCase->__invoke($healthEntityTypeId));
            return $this->successResponse(
                $healthEntityType,
                'El tipo de entidad de salud fue encontrada exitosamente.',
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
