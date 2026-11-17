<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Location\Departament;

use App\Application\LocationUseCase\DepartamentUseCase\DepartamentAllUseCase;
use App\Http\Requests\Location\Departament\DepartamentAllRequest;
use App\Http\Resources\Location\Departament\DepartamentCollection;
use App\Infrastructure\Repositories\DepartamentRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class DepartamentAllController
{
    use ApiResponser;
    use ApiMessage;
    private $departamentRepository;

    public function __construct(DepartamentRepository $departamentRepository)
    {
        $this->departamentRepository = $departamentRepository;
    }

    /**
     * @OA\Get(
     *   path="/Locations/Departaments/All",
     *   summary="Listar departamentos",
     *   description="Devuelve los departamentos con búsqueda, paginación y filtro por país.",
     *   tags={"Locations - Departaments"},
     *   @OA\Parameter(
     *     name="search", in="query", required=false,
     *     description="Término de búsqueda (por nombre)",
     *     @OA\Schema(type="string", example="Antioquia")
     *   ),
     *   @OA\Parameter(
     *     name="perPage", in="query", required=false,
     *     description="Número de registros por página",
     *     @OA\Schema(type="integer", example=10)
     *   ),
     *   @OA\Parameter(
     *     name="country_id", in="query", required=false,
     *     description="Filtrar por país",
     *     @OA\Schema(type="integer", example=48)
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Los departamentos fueron consultados exitosamente.",
     *     @OA\JsonContent(
     *       @OA\Property(property="data", type="object",
     *         @OA\Property(property="departaments", type="array",
     *           @OA\Items(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="country", type="object",
     *               description="País asociado (relación)",
     *               @OA\Property(property="id", type="integer", example=48),
     *               @OA\Property(property="name", type="string", example="Colombia"),
     *               @OA\Property(property="iso2", type="string", example="CO")
     *             ),
     *             @OA\Property(property="name", type="string", example="Antioquia"),
     *             @OA\Property(property="state_code", type="string", example="ANT"),
     *             @OA\Property(property="type", type="string", example="Department"),
     *             @OA\Property(property="latitude", type="string", example="6.5569"),
     *             @OA\Property(property="longitude", type="string", example="-75.3580"),
     *             @OA\Property(property="cities", type="array",
     *               description="Ciudades asociadas (relación)",
     *               @OA\Items(type="object",
     *                 @OA\Property(property="id", type="integer", example=101),
     *                 @OA\Property(property="name", type="string", example="Medellín")
     *               )
     *             ),
     *             @OA\Property(property="created_at", type="string", example="2025-01-01 12:00:00"),
     *             @OA\Property(property="updated_at", type="string", example="2025-02-01 12:00:00")
     *           )
     *         ),
     *         @OA\Property(property="meta", type="object",
     *           @OA\Property(property="pagination", type="object",
     *             @OA\Property(property="total", type="integer", example=33),
     *             @OA\Property(property="count", type="integer", example=10),
     *             @OA\Property(property="per_page", type="integer", example=10),
     *             @OA\Property(property="current_page", type="integer", example=1),
     *             @OA\Property(property="total_pages", type="integer", example=4)
     *           )
     *         )
     *       )
     *     )
     *   ),
     *   @OA\Response(
     *     response=422,
     *     description="Error de validación.",
     *     @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Error de validación."),
     *       @OA\Property(property="attributes", type="object", example={"search":"Término de búsqueda","perPage":"Número de registros por página","country_id":"Pais"}),
     *       @OA\Property(property="errors", type="object",
     *         @OA\Property(property="search", type="array", @OA\Items(type="string", example="Debe ser una cadena de caracteres.")),
     *         @OA\Property(property="perPage", type="array", @OA\Items(type="string", example="Debe ser un valor numérico.")),
     *         @OA\Property(property="country_id", type="array", @OA\Items(type="string", example="No existe en la base de datos."))
     *       )
     *     )
     *   ),
     *   @OA\Response(
     *     response=500,
     *     description="Error inesperado.",
     *     @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Ocurrió un error inesperado."),
     *       @OA\Property(property="error", type="string", example="Detalle del error")
     *     )
     *   )
     * )
     */
    public function __invoke(DepartamentAllRequest $request)
    {
        try {
            $departamentAllUseCase = new DepartamentAllUseCase($this->departamentRepository);
            $departaments = new DepartamentCollection($departamentAllUseCase->__invoke($request));
            return $this->successResponse(
                $departaments,
                'Los departamentos fueron consultados exitosamente.',
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
