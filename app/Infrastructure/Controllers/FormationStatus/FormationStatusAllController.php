<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\FormationStatus;

use App\Application\FormationStatusUseCase\FormationStatusAllUseCase;
use App\Http\Requests\FormationStatus\FormationStatusAllRequest;
use App\Http\Resources\FormationStatus\FormationStatusCollection;
use App\Infrastructure\Repositories\FormationStatusRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class FormationStatusAllController
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
     *     path="/FormationStatuses/All",
     *     summary="Listar estados de formación",
     *     description="Obtiene un listado paginado de estados de formación (item_id=7) con búsqueda opcional.",
     *     tags={"FormationStatuses"},
     *     @OA\Parameter(
     *         name="search", in="query", required=false, description="Término de búsqueda",
     *         @OA\Schema(type="string", maxLength=100, example="Finalizado")
     *     ),
     *     @OA\Parameter(
     *         name="perPage", in="query", required=false, description="Número de registros por página",
     *         @OA\Schema(type="integer", example=25)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Los estados de formacion consultados exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="formationStatuses", type="array",
     *                     @OA\Items(type="object",
     *                         @OA\Property(property="id", type="integer", example=3),
     *                         @OA\Property(property="item", type="object", example={"id":7,"name":"Estados de formación"}),
     *                         @OA\Property(property="name", type="string", example="En curso"),
     *                         @OA\Property(property="description", type="string", example="Programa actualmente en progreso."),
     *                         @OA\Property(property="settings", type="object", nullable=true, example={"color":"#2196F3"}),
     *                         @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-10 12:30:00"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time", example="2025-01-15 09:00:00"),
     *                         @OA\Property(property="deleted_at", type="string", nullable=true, format="date-time", example=null)
     *                     )
     *                 ),
     *                 @OA\Property(property="meta", type="object",
     *                     @OA\Property(property="pagination", type="object",
     *                         @OA\Property(property="total", type="integer", example=57),
     *                         @OA\Property(property="count", type="integer", example=25),
     *                         @OA\Property(property="per_page", type="integer", example=25),
     *                         @OA\Property(property="current_page", type="integer", example=1),
     *                         @OA\Property(property="total_pages", type="integer", example=3)
     *                     )
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="Los estados de formacion consultados exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error de validación."),
     *             @OA\Property(property="attributes", type="object", example={"search":"Término de búsqueda","perPage":"Número de registros por página"}),
     *             @OA\Property(property="errors", type="object",
     *                 example={"perPage":{"Debe ser un valor numérico."}}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al obtener los estados de formación.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ha ocurrido un error, intentelo más tarde."),
     *             @OA\Property(property="error", type="string", example="Error al obtener los estatos de formacion: detalle de la excepción")
     *         )
     *     )
     * )
     */
    public function __invoke(FormationStatusAllRequest $request)
    {
        try {
            $formationStatusAllUseCase = new FormationStatusAllUseCase($this->formationStatusRepository);
            $formationStatuses = new FormationStatusCollection($formationStatusAllUseCase->__invoke($request));
            return $this->successResponse(
                $formationStatuses,
                'Los estados de formacion consultados exitosamente.',
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
