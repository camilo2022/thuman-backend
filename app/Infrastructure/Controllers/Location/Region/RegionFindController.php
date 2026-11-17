<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Location\Region;

use App\Application\LocationUseCase\RegionUseCase\RegionFindUseCase;
use App\Domain\Location\Region\Values\RegionId;
use App\Http\Requests\Location\Region\RegionFindRequest;
use App\Http\Resources\Location\Region\RegionResource;
use App\Infrastructure\Repositories\RegionRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class RegionFindController
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
     *     path="/Locations/Regions/Find/{regionId}",
     *     summary="Encontrar región",
     *     description="Obtiene una región por **ID** (parámetro de ruta) o por **nombre** (query). Si se envía `regionId`, el parámetro `name` es ignorado.",
     *     tags={"Locations - Regions"},
     *     @OA\Parameter(
     *         name="regionId",
     *         in="path",
     *         required=false,
     *         description="ID de la región a consultar (opcional).",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         required=false,
     *         description="Nombre exacto de la región (requerido si no se envía `regionId`).",
     *         @OA\Schema(type="string", example="Africa")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="La región fue encontrada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="region", type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Africa"),
     *                     @OA\Property(
     *                         property="translations",
     *                         type="object",
     *                         example={"ko":"아프리카","pt-BR":"África","pt":"África","nl":"Afrika","hr":"Afrika","fa":"آفریقا","de":"Afrika","es":"África","fr":"Afrique","ja":"アフリカ","it":"Africa","zh-CN":"非洲","tr":"Afrika","ru":"Африка","uk":"Африка","pl":"Afryka"}
     *                     ),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-18 10:00:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-18 11:00:00")
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="La region fue encontrada exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="La región no existe.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ocurrió un error."),
     *             @OA\Property(property="error", type="string", example="La region no existe: No se encontraron resultados."),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error de validación."),
     *             @OA\Property(property="attributes", type="object",
     *                 @OA\Property(property="name", type="string", example="Region")
     *             ),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="name", type="array", @OA\Items(type="string", example="No existe en la base de datos.")),
     *                 @OA\Property(property="name_required", type="array", @OA\Items(type="string", example="Es requerido."))
     *             ),
     *             @OA\Property(property="status", type="integer", example=422)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al buscar la región.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ocurrió un error."),
     *             @OA\Property(property="error", type="string", example="Error al buscar la region: Error en la base de datos."),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */
    public function __invoke(RegionFindRequest $request, int|null $regionId = null)
    {
        try {
            $regionId = new RegionId($regionId);
            $regionFindUseCase = new RegionFindUseCase($this->regionRepository);
            $region = new RegionResource($regionFindUseCase->__invoke($request, $regionId));
            return $this->successResponse(
                $region,
                'La region fue encontrada exitosamente.',
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
