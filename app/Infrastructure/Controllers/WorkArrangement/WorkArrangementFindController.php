<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\WorkArrangement;

use App\Application\WorkArrangementUseCase\WorkArrangementFindUseCase;
use App\Domain\WorkArrangement\Values\WorkArrangementId;
use App\Http\Resources\WorkArrangement\WorkArrangementResource;
use App\Infrastructure\Repositories\WorkArrangementRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class WorkArrangementFindController
{
    use ApiResponser;
    use ApiMessage;
    private $workArrangementRepository;

    public function __construct(WorkArrangementRepository $workArrangementRepository)
    {
        $this->workArrangementRepository = $workArrangementRepository;
    }

    /**
     * @OA\Get(
     *     path="/WorkArrangements/Find/{workArrangementId}",
     *     summary="Encontrar modalidad de trabajo por ID",
     *     description="Retorna el detalle de una modalidad de trabajo específica (incluye eliminados lógicamente).",
     *     tags={"WorkArrangements"},
     *     @OA\Parameter(
     *         name="workArrangementId",
     *         in="path",
     *         required=true,
     *         description="ID de la modalidad de trabajo a consultar",
     *         @OA\Schema(type="integer", example=3)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="La modalidad de trabajo fue encontrada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="workArrangement", type="object",
     *                     @OA\Property(property="id", type="integer", example=3),
     *                     @OA\Property(property="item_id", type="integer", example=3),
     *                     @OA\Property(property="name", type="string", example="Híbrido"),
     *                     @OA\Property(property="description", type="string", example="Trabajo combinado entre remoto y presencial."),
     *                     @OA\Property(property="settings", type="object", example={"order":2}),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-10 09:30:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-02-15 12:45:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="La modalidad de trabajo fue encontrada exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Modalidad de trabajo no encontrada.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se ha producido un error."),
     *             @OA\Property(property="error", type="string", example="La modalidad de trabajo no existe: No se encontró el ID especificado.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al buscar la modalidad de trabajo.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se ha producido un error."),
     *             @OA\Property(property="error", type="string", example="Error al buscar la modalidad de trabajo: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(int $workArrangementId)
    {
        try {
            $workArrangementId = new WorkArrangementId($workArrangementId ?? null);
            $workArrangementFindUseCase = new WorkArrangementFindUseCase($this->workArrangementRepository);
            $workArrangement = new WorkArrangementResource($workArrangementFindUseCase->__invoke($workArrangementId));
            return $this->successResponse(
                $workArrangement,
                'La modalidad de trabajo fue encontrada exitosamente.',
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
