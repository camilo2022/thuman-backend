<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\WorkDay;

use App\Application\WorkDayUseCase\WorkDayStoreUseCase;
use App\Http\Requests\WorkDay\WorkDayStoreRequest;
use App\Http\Resources\WorkDay\WorkDayResource;
use App\Infrastructure\Repositories\WorkDayRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class WorkDayStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $workDayRepository;

    public function __construct(WorkDayRepository $workDayRepository)
    {
        $this->workDayRepository = $workDayRepository;
    }

    /**
     * @OA\Post(
     *     path="/WorkDays/Store",
     *     summary="Registrar una nueva jornada laboral",
     *     description="Crea una nueva jornada laboral con los datos proporcionados.",
     *     tags={"WorkDays"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"item_id","name","description"},
     *             @OA\Property(property="item_id", type="integer", example=3, description="Debe ser 3 (categorización fija)"),
     *             @OA\Property(property="name", type="string", maxLength=80, example="Nocturno"),
     *             @OA\Property(property="description", type="string", maxLength=255, example="Horario laboral de noche.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="La jornada laboral registrado exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="workDay", type="object",
     *                     @OA\Property(property="id", type="integer", example=7),
     *                     @OA\Property(property="item_id", type="integer", example=2),
     *                     @OA\Property(property="name", type="string", example="Nocturno"),
     *                     @OA\Property(property="description", type="string", example="Horario laboral de noche."),
     *                     @OA\Property(property="settings", type="object", example={"order":2}),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-03-01 10:15:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-03-01 10:15:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="La jornada laboral registrado exitosamente."),
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
     *         description="Error inesperado al guardar la jornada laboral.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se ha producido un error."),
     *             @OA\Property(property="error", type="string", example="Error al guardar la jornada laboral: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(WorkDayStoreRequest $request)
    {
        try {
            $workDayStoreUseCase = new WorkDayStoreUseCase($this->workDayRepository);
            $workDay = new WorkDayResource($workDayStoreUseCase->__invoke($request));
            return $this->successResponse(
                $workDay,
                'La jornada laboral fue registrada exitosamente.',
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
