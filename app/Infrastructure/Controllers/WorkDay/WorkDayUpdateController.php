<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\WorkDay;

use App\Application\WorkDayUseCase\WorkDayUpdateUseCase;
use App\Domain\WorkDay\Values\WorkDayId;
use App\Http\Requests\WorkDay\WorkDayUpdateRequest;
use App\Http\Resources\WorkDay\WorkDayResource;
use App\Infrastructure\Repositories\WorkDayRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class WorkDayUpdateController
{
    use ApiResponser;
    use ApiMessage;
    private $workDayRepository;

    public function __construct(WorkDayRepository $workDayRepository)
    {
        $this->workDayRepository = $workDayRepository;
    }

    /**
     * @OA\Put(
     *     path="/WorkDays/Update/{workDayId}",
     *     summary="Actualizar una jornada laboral",
     *     description="Actualiza una jornada laboral existente por su ID.",
     *     tags={"WorkDays"},
     *     @OA\Parameter(
     *         name="workDayId",
     *         in="path",
     *         required=true,
     *         description="ID de la jornada laboral a actualizar",
     *         @OA\Schema(type="integer", example=2)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"item_id","name","description"},
     *             @OA\Property(property="item_id", type="integer", example=2, description="Debe ser 2 (categorización fija)"),
     *             @OA\Property(property="name", type="string", maxLength=80, example="Nocturno"),
     *             @OA\Property(property="description", type="string", maxLength=255, example="Horario laboral de noche")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="La jornada laboral fue actualizado exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="workDay", type="object",
     *                     @OA\Property(property="id", type="integer", example=2),
     *                     @OA\Property(property="item_id", type="integer", example=2),
     *                     @OA\Property(property="name", type="string", example="Nocturno"),
     *                     @OA\Property(property="description", type="string", example="Horario laboral de noche"),
     *                     @OA\Property(property="settings", type="object", example={"order":2}),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-10 09:30:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-03-02 14:10:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="La jornada laboral fue actualizado exitosamente."),
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
     *         response=422,
     *         description="Error de validación.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error de validación."),
     *             @OA\Property(property="attributes", type="object",
     *                 @OA\Property(property="item_id", type="string", example="Categorización"),
     *                 @OA\Property(property="name", type="string", example="Nombre"),
     *                 @OA\Property(property="description", type="string", example="Descripción")
     *             ),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="item_id", type="array", @OA\Items(type="string", example="No existe en la base de datos.")),
     *                 @OA\Property(property="name", type="array", @OA\Items(type="string", example="Ya ha sido tomado.")),
     *                 @OA\Property(property="description", type="array", @OA\Items(type="string", example="No debe exceder los 255 caracteres."))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al actualizar la jornada laboral.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se ha producido un error."),
     *             @OA\Property(property="error", type="string", example="Error al actualizar la jornada laboral: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(WorkDayUpdateRequest $request, int $workDayId)
    {
        try {
            $workDayId = new WorkDayId($workDayId ?? null);
            $workDayUpdateUseCase = new WorkDayUpdateUseCase($this->workDayRepository);
            $workDay = new WorkDayResource($workDayUpdateUseCase->__invoke($request, $workDayId));
            return $this->successResponse(
                $workDay,
                'La jornada laboral fue actualizada exitosamente.',
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
