<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\WorkDay;

use App\Application\WorkDayUseCase\WorkDayFindUseCase;
use App\Domain\WorkDay\Values\WorkDayId;
use App\Http\Resources\WorkDay\WorkDayResource;
use App\Infrastructure\Repositories\WorkDayRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class WorkDayFindController
{
    use ApiResponser;
    use ApiMessage;
    private $workDayRepository;

    public function __construct(WorkDayRepository $workDayRepository)
    {
        $this->workDayRepository = $workDayRepository;
    }

    /**
     * @OA\Get(
     *     path="/WorkDays/Find/{workDayId}",
     *     summary="Encontrar jornada laboral por ID",
     *     description="Retorna el detalle de una jornada laboral específica (incluye eliminados lógicamente).",
     *     tags={"WorkDays"},
     *     @OA\Parameter(
     *         name="workDayId",
     *         in="path",
     *         required=true,
     *         description="ID de la jornada laboral a consultar",
     *         @OA\Schema(type="integer", example=3)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="La jornada laboral fue encontrada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="workDay", type="object",
     *                     @OA\Property(property="id", type="integer", example=3),
     *                     @OA\Property(property="item_id", type="integer", example=3),
     *                     @OA\Property(property="name", type="string", example="Nocturno"),
     *                     @OA\Property(property="description", type="string", example="Horario laboral de noche."),
     *                     @OA\Property(property="settings", type="object", example={"order":2}),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-10 09:30:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-02-15 12:45:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="La jornada laboral fue encontrada exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Jornada Laboral no encontrada.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se ha producido un error."),
     *             @OA\Property(property="error", type="string", example="La jornada laboral no existe: No se encontró el ID especificado.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al buscar la jornada laboral.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se ha producido un error."),
     *             @OA\Property(property="error", type="string", example="Error al buscar la jornada laboral: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(int $workDayId)
    {
        try {
            $workDayId = new WorkDayId($workDayId ?? null);
            $workDayFindUseCase = new WorkDayFindUseCase($this->workDayRepository);
            $workDay = new WorkDayResource($workDayFindUseCase->__invoke($workDayId));
            return $this->successResponse(
                $workDay,
                'La jornada laboral fue encontrada exitosamente.',
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
