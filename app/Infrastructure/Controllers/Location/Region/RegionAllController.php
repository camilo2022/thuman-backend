<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Location\Region;

use App\Application\LocationUseCase\RegionUseCase\RegionAllUseCase;
use App\Http\Requests\Location\Region\RegionAllRequest;
use App\Http\Resources\Location\Region\RegionCollection;
use App\Infrastructure\Repositories\RegionRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class RegionAllController
{
    use ApiResponser;
    use ApiMessage;
    private $regionRepository;

    public function __construct(RegionRepository $regionRepository)
    {
        $this->regionRepository = $regionRepository;
    }

    /**
     * @OA\Get(
     *     path="/Locations/Regions/All",
     *     summary="Listar regiones",
     *     description="Retorna el listado paginado de regiones con sus traducciones y subregiones asociadas.",
     *     tags={"Locations - Regions"},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         required=false,
     *         description="Término de búsqueda",
     *         @OA\Schema(type="string", maxLength=100, example="afr")
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
     *         description="Las regiones fueron consultadas exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="regions", type="array",
     *                     @OA\Items(type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="name", type="string", example="Africa"),
     *                         @OA\Property(
     *                             property="translations",
     *                             type="object",
     *                             example={"ko":"아프리카","pt-BR":"África","pt":"África","nl":"Afrika","hr":"Afrika","fa":"آفریقا","de":"Afrika","es":"África","fr":"Afrique","ja":"アフリカ","it":"Africa","zh-CN":"非洲","tr":"Afrika","ru":"Африка","uk":"Африка","pl":"Afryka"}
     *                         ),
     *                         @OA\Property(
     *                             property="subregions",
     *                             type="array",
     *                             description="Subregiones asociadas (si se cargan).",
     *                             @OA\Items(type="object",
     *                                 @OA\Property(property="id", type="integer", example=10),
     *                                 @OA\Property(property="name", type="string", example="Eastern Africa")
     *                             )
     *                         ),
     *                         @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-18 10:00:00"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-18 11:00:00")
     *                     )
     *                 ),
     *                 @OA\Property(property="meta", type="object",
     *                     @OA\Property(property="pagination", type="object",
     *                         @OA\Property(property="total", type="integer", example=6),
     *                         @OA\Property(property="count", type="integer", example=6),
     *                         @OA\Property(property="per_page", type="integer", example=15),
     *                         @OA\Property(property="current_page", type="integer", example=1),
     *                         @OA\Property(property="total_pages", type="integer", example=1)
     *                     )
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="Las regiones fueron consultadas exitosamente."),
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
     *             ),
     *             @OA\Property(property="status", type="integer", example=422)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al obtener las regiones.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ocurrió un error."),
     *             @OA\Property(property="error", type="string", example="Error al obtener las regiones: Error en la base de datos."),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */
    public function __invoke(RegionAllRequest $request)
    {
        try {
            $regionAllUseCase = new RegionAllUseCase($this->regionRepository);
            $regions = new RegionCollection($regionAllUseCase->__invoke($request));
            return $this->successResponse(
                $regions,
                'Las regiones fueron consultadas exitosamente.',
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
