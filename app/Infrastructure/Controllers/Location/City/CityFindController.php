<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Location\City;

use App\Application\LocationUseCase\CityUseCase\CityFindUseCase;
use App\Domain\Location\City\Values\CityId;
use App\Http\Requests\Location\City\CityFindRequest;
use App\Http\Resources\Location\City\CityResource;
use App\Infrastructure\Repositories\CityRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class CityFindController
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
     *   path="/Locations/Cities/Find/{cityId}",
     *   summary="Encontrar una ciudad",
     *   description="Busca una ciudad por ID. Si no se envía el ID, se puede enviar 'name' por query para búsqueda alternativa.",
     *   tags={"Locations - Cities"},
     *   @OA\Parameter(
     *     name="cityId", in="path", required=false,
     *     description="ID de la ciudad (opcional si se usa 'name' por query)",
     *     @OA\Schema(type="integer", example=101)
     *   ),
     *   @OA\Parameter(
     *     name="name", in="query", required=false,
     *     description="Nombre de la ciudad (requerido cuando no se envía 'cityId')",
     *     @OA\Schema(type="string", example="Medellín")
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="La ciudad fue encontrada exitosamente.",
     *     @OA\JsonContent(
     *       @OA\Property(property="data", type="object",
     *         @OA\Property(property="city", type="object",
     *           @OA\Property(property="id", type="integer", example=101),
     *           @OA\Property(property="departament_id", type="integer", example=5),
     *           @OA\Property(property="name", type="string", example="Medellín"),
     *           @OA\Property(property="latitude", type="string", example="6.2442"),
     *           @OA\Property(property="longitude", type="string", example="-75.5812"),
     *           @OA\Property(property="created_at", type="string", example="2025-01-01 12:00:00"),
     *           @OA\Property(property="updated_at", type="string", example="2025-02-01 12:00:00")
     *         )
     *       )
     *     )
     *   ),
     *   @OA\Response(
     *     response=422,
     *     description="Error de validación.",
     *     @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Error de validación."),
     *       @OA\Property(property="attributes", type="object", example={"name":"Ciudad"}),
     *       @OA\Property(property="errors", type="object",
     *         @OA\Property(property="name", type="array", @OA\Items(type="string", example="No existe en la base de datos."))
     *       )
     *     )
     *   ),
     *   @OA\Response(
     *     response=404,
     *     description="La ciudad no existe.",
     *     @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="La ciudad no existe.")
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
    public function __invoke(CityFindRequest $request, int|null $cityId = null)
    {
        try {
            $cityId = new CityId($cityId);
            $cityFindUseCase = new CityFindUseCase($this->cityRepository);
            $city = new CityResource($cityFindUseCase->__invoke($request, $cityId));
            return $this->successResponse(
                $city,
                'La ciudad fue encontrada exitosamente.',
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
