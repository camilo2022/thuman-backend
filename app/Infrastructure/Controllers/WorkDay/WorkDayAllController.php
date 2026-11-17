<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\WorkDay;

use App\Application\WorkDayUseCase\WorkDayAllUseCase;
use App\Http\Requests\WorkDay\WorkDayAllRequest;
use App\Http\Resources\WorkDay\WorkDayCollection;
use App\Infrastructure\Repositories\WorkDayRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class WorkDayAllController
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
     *     path="/WorkDays/All",
     *     summary="Listar jornadas laborales",
     *     description="Obtiene un listado paginado de jornadas laborales (incluye eliminados lógicamente).",
     *     tags={"WorkDays"},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         required=false,
     *         description="Término de búsqueda por nombre o descripción",
     *         @OA\Schema(type="string", maxLength=100, example="Nocturno")
     *     ),
     *     @OA\Parameter(
     *         name="perPage",
     *         in="query",
     *         required=false,
     *         description="Número de registros por página",
     *         @OA\Schema(type="integer", example=15)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Las jornadas laborales consultados exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="workDays", type="array",
     *                     @OA\Items(type="object",
     *                         @OA\Property(property="id", type="integer", example=2),
     *                         @OA\Property(property="item", type="object",
     *                             @OA\Property(property="id", type="integer", example=2),
     *                             @OA\Property(property="name", type="string", example="Jornadas Laborales")
     *                         ),
     *                         @OA\Property(property="name", type="string", example="Nocturno"),
     *                         @OA\Property(property="description", type="string", example="Horario laboral de noche."),
     *                         @OA\Property(property="settings", type="object", example={}),
     *                         @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-10 09:30:00"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time", example="2025-02-15 12:45:00"),
     *                         @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                     )
     *                 ),
     *                 @OA\Property(property="meta", type="object",
     *                     @OA\Property(property="pagination", type="object",
     *                         @OA\Property(property="total", type="integer", example=18),
     *                         @OA\Property(property="count", type="integer", example=10),
     *                         @OA\Property(property="per_page", type="integer", example=10),
     *                         @OA\Property(property="current_page", type="integer", example=1),
     *                         @OA\Property(property="total_pages", type="integer", example=2)
     *                     )
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="Las jornadas laborales consultados exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error de validación."),
     *             @OA\Property(property="attributes", type="object",
     *                 @OA\Property(property="search", type="string", example="Término de búsqueda"),
     *                 @OA\Property(property="perPage", type="string", example="Número de registros por página")
     *             ),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="search", type="array", @OA\Items(type="string", example="Debe ser una cadena de caracteres.")),
     *                 @OA\Property(property="perPage", type="array", @OA\Items(type="string", example="Debe ser un valor numérico."))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al obtener las jornadas laborales.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se ha producido un error."),
     *             @OA\Property(property="error", type="string", example="Error al obtener las jornadas laborales: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(WorkDayAllRequest $request)
    {
        try {
            $workDayAllUseCase = new WorkDayAllUseCase($this->workDayRepository);
            $workDays = new WorkDayCollection($workDayAllUseCase->__invoke($request));
            return $this->successResponse(
                $workDays,
                'Las jornadas laborales consultados exitosamente.',
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
