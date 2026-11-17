<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Location\Subregion;

use App\Application\LocationUseCase\SubregionUseCase\SubregionAllUseCase;
use App\Http\Requests\Location\Subregion\SubregionAllRequest;
use App\Http\Resources\Location\Subregion\SubregionCollection;
use App\Infrastructure\Repositories\SubregionRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class SubregionAllController
{
    use ApiResponser;
    use ApiMessage;
    private $subregionRepository;

    public function __construct(SubregionRepository $subregionRepository)
    {
        $this->subregionRepository = $subregionRepository;
    }

    /**
     * @OA\Get(
     *     path="/Locations/Subregions/All",
     *     summary="Listar subregiones",
     *     description="Retorna un listado paginado de subregiones. Permite filtrar por búsqueda libre y por región.",
     *     tags={"Locations - Subregions"},
     *     @OA\Parameter(
     *         name="search", in="query", required=false,
     *         description="Término de búsqueda",
     *         @OA\Schema(type="string", maxLength=100), example="Africa"
     *     ),
     *     @OA\Parameter(
     *         name="perPage", in="query", required=false,
     *         description="Número de registros por página",
     *         @OA\Schema(type="integer"), example=10
     *     ),
     *     @OA\Parameter(
     *         name="region_id", in="query", required=false,
     *         description="ID de la región para filtrar",
     *         @OA\Schema(type="integer"), example=1
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Las subregiones fueron consultadas exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="regions", type="array",
     *                     @OA\Items(type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="region", type="object",
     *                             @OA\Property(property="id", type="integer", example=1),
     *                             @OA\Property(property="name", type="string", example="Africa")
     *                         ),
     *                         @OA\Property(property="name", type="string", example="Northern Africa"),
     *                         @OA\Property(
     *                             property="translations", type="object",
     *                             example={"es":"Norte de África","en":"Northern Africa","fr":"Afrique du Nord","de":"Nordafrika","zh-CN":"北部非洲"}
     *                         ),
     *                         @OA\Property(property="countries", type="array",
     *                             @OA\Items(type="object",
     *                                 @OA\Property(property="id", type="integer", example=12),
     *                                 @OA\Property(property="name", type="string", example="Egypt")
     *                             )
     *                         ),
     *                         @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-18 10:00:00"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-18 11:00:00")
     *                     )
     *                 ),
     *                 @OA\Property(property="meta", type="object",
     *                     @OA\Property(property="pagination", type="object",
     *                         @OA\Property(property="total", type="integer", example=123),
     *                         @OA\Property(property="count", type="integer", example=10),
     *                         @OA\Property(property="per_page", type="integer", example=10),
     *                         @OA\Property(property="current_page", type="integer", example=1),
     *                         @OA\Property(property="total_pages", type="integer", example=13)
     *                     )
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="Las subregiones fueron consultadas exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error de validación."),
     *             @OA\Property(property="attributes", type="object"),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="search", type="array", @OA\Items(type="string", example="Debe ser una cadena de caracteres.")),
     *                 @OA\Property(property="perPage", type="array", @OA\Items(type="string", example="Debe ser un valor numérico.")),
     *                 @OA\Property(property="region_id", type="array", @OA\Items(type="string", example="No existe en la base de datos."))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al obtener las subregiones.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error al obtener las subregiones: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(SubregionAllRequest $request)
    {
        try {
            $subregionAllUseCase = new SubregionAllUseCase($this->subregionRepository);
            $subregions = new SubregionCollection($subregionAllUseCase->__invoke($request));
            return $this->successResponse(
                $subregions,
                'Las subregiones fueron consultadas exitosamente.',
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
