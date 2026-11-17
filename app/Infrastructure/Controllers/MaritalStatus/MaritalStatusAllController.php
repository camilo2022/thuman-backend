<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\MaritalStatus;

use App\Application\MaritalStatusUseCase\MaritalStatusAllUseCase;
use App\Http\Requests\MaritalStatus\MaritalStatusAllRequest;
use App\Http\Resources\MaritalStatus\MaritalStatusCollection;
use App\Infrastructure\Repositories\MaritalStatusRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class MaritalStatusAllController
{
    use ApiResponser;
    use ApiMessage;
    private $maritalStatusRepository;

    public function __construct(MaritalStatusRepository $maritalStatusRepository)
    {
        $this->maritalStatusRepository = $maritalStatusRepository;
    }

    /**
     * @OA\Get(
     *     path="/MaritalStatus/All",
     *     summary="Listar estados civiles",
     *     description="Retorna un listado paginado de estados civiles con opción de búsqueda.",
     *     tags={"MaritalStatus"},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         required=false,
     *         description="Término de búsqueda",
     *         @OA\Schema(type="string", example="viudo")
     *     ),
     *     @OA\Parameter(
     *         name="perPage",
     *         in="query",
     *         required=false,
     *         description="Número de registros por página",
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Los estados civiles consultados exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="maritalStatuses", type="array",
     *                     @OA\Items(type="object",
     *                         @OA\Property(property="id", type="integer", example=3),
     *                         @OA\Property(property="item", type="object",
     *                             @OA\Property(property="id", type="integer", example=12),
     *                             @OA\Property(property="name", type="string", example="Estados Civiles")
     *                         ),
     *                         @OA\Property(property="name", type="string", example="Casado"),
     *                         @OA\Property(property="description", type="string", example="Persona con vínculo matrimonial vigente."),
     *                         @OA\Property(property="settings", type="object", example={"visible":true}),
     *                         @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-18 10:00:00"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-18 11:00:00"),
     *                         @OA\Property(property="deleted_at", type="string", nullable=true, format="date-time", example=null)
     *                     )
     *                 ),
     *                 @OA\Property(property="meta", type="object",
     *                     @OA\Property(property="pagination", type="object",
     *                         @OA\Property(property="total", type="integer", example=4),
     *                         @OA\Property(property="count", type="integer", example=4),
     *                         @OA\Property(property="per_page", type="integer", example=10),
     *                         @OA\Property(property="current_page", type="integer", example=1),
     *                         @OA\Property(property="total_pages", type="integer", example=1)
     *                     )
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="Los estados civiles consultados exitosamente."),
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
     *                 @OA\Property(property="perPage", type="array",
     *                     @OA\Items(type="string", example="Debe ser un valor numérico.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al obtener los estados civiles.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ha ocurrido un error inesperado."),
     *             @OA\Property(property="error", type="string", example="Error al obtener los estados civiles: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(MaritalStatusAllRequest $request)
    {
        try {
            $maritalStatusAllUseCase = new MaritalStatusAllUseCase($this->maritalStatusRepository);
            $maritalStatuses = new MaritalStatusCollection($maritalStatusAllUseCase->__invoke($request));
            return $this->successResponse(
                $maritalStatuses,
                'Los estados civiles consultados exitosamente.',
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
