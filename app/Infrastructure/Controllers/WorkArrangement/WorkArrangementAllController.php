<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\WorkArrangement;

use App\Application\WorkArrangementUseCase\WorkArrangementAllUseCase;
use App\Http\Requests\WorkArrangement\WorkArrangementAllRequest;
use App\Http\Resources\WorkArrangement\WorkArrangementCollection;
use App\Infrastructure\Repositories\WorkArrangementRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class WorkArrangementAllController
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
     *     path="/WorkArrangements/All",
     *     summary="Listar modalidades de trabajo",
     *     description="Obtiene un listado paginado de modalidades de trabajo (incluye eliminados lógicamente).",
     *     tags={"WorkArrangements"},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         required=false,
     *         description="Término de búsqueda por nombre o descripción",
     *         @OA\Schema(type="string", maxLength=100, example="Híbrido")
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
     *         description="Las modalidades de trabajo consultados exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="workArrangements", type="array",
     *                     @OA\Items(type="object",
     *                         @OA\Property(property="id", type="integer", example=3),
     *                         @OA\Property(property="item", type="object",
     *                             @OA\Property(property="id", type="integer", example=3),
     *                             @OA\Property(property="name", type="string", example="Modalidades de Trabajo")
     *                         ),
     *                         @OA\Property(property="name", type="string", example="Híbrido"),
     *                         @OA\Property(property="description", type="string", example="Trabajo combinado entre remoto y presencial."),
     *                         @OA\Property(property="settings", type="object", example={"order":2}),
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
     *             @OA\Property(property="message", type="string", example="Las modalidades de trabajo consultados exitosamente."),
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
     *         description="Error inesperado al obtener las modalidades de trabajo.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se ha producido un error."),
     *             @OA\Property(property="error", type="string", example="Error al obtener las modalidades de trabajo: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(WorkArrangementAllRequest $request)
    {
        try {
            $workArrangementAllUseCase = new WorkArrangementAllUseCase($this->workArrangementRepository);
            $workArrangements = new WorkArrangementCollection($workArrangementAllUseCase->__invoke($request));
            return $this->successResponse(
                $workArrangements,
                'Las modalidades de trabajo consultados exitosamente.',
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
