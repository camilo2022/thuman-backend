<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\FormationStatus;

use App\Application\FormationStatusUseCase\FormationStatusFindUseCase;
use App\Domain\FormationStatus\Values\FormationStatusId;
use App\Http\Resources\FormationStatus\FormationStatusResource;
use App\Infrastructure\Repositories\FormationStatusRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class FormationStatusFindController
{
    use ApiResponser;
    use ApiMessage;
    private $formationStatusRepository;

    public function __construct(FormationStatusRepository $formationStatusRepository)
    {
        $this->formationStatusRepository = $formationStatusRepository;
    }

    /**
     * @OA\Get(
     *     path="/FormationStatuses/Find/{formationStatusId}",
     *     summary="Encontrar estado de formación por ID",
     *     description="Retorna un estado de formación (item_id=7) por su identificador.",
     *     tags={"FormationStatuses"},
     *     @OA\Parameter(
     *         name="formationStatusId",
     *         in="path",
     *         required=true,
     *         description="ID del estado de formación",
     *         @OA\Schema(type="integer", example=3)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="El estado de formacion fue encontrada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="formationStatus", type="object",
     *                     @OA\Property(property="id", type="integer", example=3),
     *                     @OA\Property(property="item_id", type="integer", example=7),
     *                     @OA\Property(property="name", type="string", example="Finalizado"),
     *                     @OA\Property(property="description", type="string", example="Programa completado con éxito."),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-10 12:30:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-01-15 09:00:00"),
     *                     @OA\Property(property="deleted_at", type="string", nullable=true, format="date-time", example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="El estado de formacion fue encontrada exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Estado de formación no encontrado.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ha ocurrido un error, intentelo más tarde."),
     *             @OA\Property(property="error", type="string", example="El estado de formacion no existe: No se encontró el ID especificado.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al buscar el estado de formación.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ha ocurrido un error, intentelo más tarde."),
     *             @OA\Property(property="error", type="string", example="Error al buscar el estado de formacion: detalle de la excepción")
     *         )
     *     )
     * )
     */
    public function __invoke(int $formationStatusId)
    {
        try {
            $formationStatusId = new FormationStatusId($formationStatusId ?? null);
            $formationStatusFindUseCase = new FormationStatusFindUseCase($this->formationStatusRepository);
            $formationStatus = new FormationStatusResource($formationStatusFindUseCase->__invoke($formationStatusId));
            return $this->successResponse(
                $formationStatus,
                'El estado de formacion fue encontrada exitosamente.',
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
