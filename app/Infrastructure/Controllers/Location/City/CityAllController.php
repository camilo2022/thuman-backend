<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Location\City;

use App\Application\LocationUseCase\CityUseCase\CityAllUseCase;
use App\Http\Requests\Location\City\CityAllRequest;
use App\Http\Resources\Location\City\CityCollection;
use App\Infrastructure\Repositories\CityRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class CityAllController
{
    use ApiResponser;
    use ApiMessage;
    private $cityRepository;

    public function __construct(CityRepository $cityRepository)
    {
        $this->cityRepository = $cityRepository;
    }

    /**
     * @OA\Get(
     *   path="/Locations/Cities/All",
     *   summary="Listar ciudades",
     *   description="Devuelve las ciudades con búsqueda, paginación y filtro por departamento.",
     *   tags={"Locations - Cities"},
     *   @OA\Parameter(
     *     name="search", in="query", required=false,
     *     description="Término de búsqueda (por nombre)",
     *     @OA\Schema(type="string", example="Medellín")
     *   ),
     *   @OA\Parameter(
     *     name="perPage", in="query", required=false,
     *     description="Número de registros por página",
     *     @OA\Schema(type="integer", example=10)
     *   ),
     *   @OA\Parameter(
     *     name="departament_id", in="query", required=false,
     *     description="Filtrar por departamento",
     *     @OA\Schema(type="integer", example=5)
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Las ciudades fueron consultadas exitosamente.",
     *     @OA\JsonContent(
     *       @OA\Property(property="data", type="object",
     *         @OA\Property(property="cities", type="array",
     *           @OA\Items(
     *             @OA\Property(property="id", type="integer", example=101),
     *             @OA\Property(property="departament", type="object",
     *               description="Departamento asociado (relación)",
     *               @OA\Property(property="id", type="integer", example=5),
     *               @OA\Property(property="name", type="string", example="Antioquia"),
     *               @OA\Property(property="state_code", type="string", example="ANT")
     *             ),
     *             @OA\Property(property="name", type="string", example="Medellín"),
     *             @OA\Property(property="latitude", type="string", example="6.2442"),
     *             @OA\Property(property="longitude", type="string", example="-75.5812"),
     *             @OA\Property(property="created_at", type="string", example="2025-01-01 12:00:00"),
     *             @OA\Property(property="updated_at", type="string", example="2025-02-01 12:00:00")
     *           )
     *         ),
     *         @OA\Property(property="meta", type="object",
     *           @OA\Property(property="pagination", type="object",
     *             @OA\Property(property="total", type="integer", example=1250),
     *             @OA\Property(property="count", type="integer", example=10),
     *             @OA\Property(property="per_page", type="integer", example=10),
     *             @OA\Property(property="current_page", type="integer", example=1),
     *             @OA\Property(property="total_pages", type="integer", example=125)
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
     *       @OA\Property(property="attributes", type="object", example={"search":"Término de búsqueda","perPage":"Número de registros por página","departament_id":"Departamento"}),
     *       @OA\Property(property="errors", type="object",
     *         @OA\Property(property="search", type="array", @OA\Items(type="string", example="Debe ser una cadena de caracteres.")),
     *         @OA\Property(property="perPage", type="array", @OA\Items(type="string", example="Debe ser un valor numérico.")),
     *         @OA\Property(property="departament_id", type="array", @OA\Items(type="string", example="No existe en la base de datos."))
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
    public function __invoke(CityAllRequest $request)
    {
        try {
            $cityAllUseCase = new CityAllUseCase($this->cityRepository);
            $cities = new CityCollection($cityAllUseCase->__invoke($request));
            return $this->successResponse(
                $cities,
                'Las ciudades fueron consultadas exitosamente.',
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
