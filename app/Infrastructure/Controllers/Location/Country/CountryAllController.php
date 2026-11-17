<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Location\Country;

use App\Application\LocationUseCase\CountryUseCase\CountryAllUseCase;
use App\Http\Requests\Location\Country\CountryAllRequest;
use App\Http\Resources\Location\Country\CountryCollection;
use App\Infrastructure\Repositories\CountryRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class CountryAllController
{
    use ApiResponser;
    use ApiMessage;
    private $countryRepository;

    public function __construct(CountryRepository $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }

    /**
     * @OA\Get(
     *     path="/Locations/Countries/All",
     *     summary="Obtener listado de países",
     *     description="Devuelve todos los países con opción de búsqueda, paginación y filtrado por subregión.",
     *     tags={"Locations - Countries"},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Término de búsqueda (nombre del país)",
     *         required=false,
     *         @OA\Schema(type="string", example="Colombia")
     *     ),
     *     @OA\Parameter(
     *         name="perPage",
     *         in="query",
     *         description="Número de registros por página",
     *         required=false,
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Parameter(
     *         name="subregion_id",
     *         in="query",
     *         description="Filtrar por subregión",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Listado de países obtenido exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="countries", type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="subregion", type="string", example="South America"),
     *                         @OA\Property(property="name", type="string", example="Colombia"),
     *                         @OA\Property(property="iso3", type="string", example="COL"),
     *                         @OA\Property(property="iso2", type="string", example="CO"),
     *                         @OA\Property(property="numeric_code", type="string", example="170"),
     *                         @OA\Property(property="phone_code", type="string", example="+57"),
     *                         @OA\Property(property="currency", type="string", example="COP"),
     *                         @OA\Property(property="currency_name", type="string", example="Colombian Peso"),
     *                         @OA\Property(property="currency_symbol", type="string", example="$"),
     *                         @OA\Property(property="tld", type="string", example=".co"),
     *                         @OA\Property(property="native", type="string", example="Colombia"),
     *                         @OA\Property(property="nationality", type="string", example="Colombian"),
     *                         @OA\Property(property="latitude", type="string", example="4.5709"),
     *                         @OA\Property(property="longitude", type="string", example="-74.2973"),
     *                         @OA\Property(property="emoji", type="string", example="🇨🇴"),
     *                         @OA\Property(property="emojiU", type="string", example="U+1F1E8 U+1F1F4"),
     *                         @OA\Property(property="translations", type="object",
     *                             @OA\Property(property="es", type="string", example="Colombia"),
     *                             @OA\Property(property="fr", type="string", example="Colombie"),
     *                             @OA\Property(property="en", type="string", example="Colombia")
     *                         ),
     *                         @OA\Property(property="departaments", type="array",
     *                             @OA\Items(type="string", example="Antioquia")
     *                         ),
     *                         @OA\Property(property="created_at", type="string", example="2025-01-01 12:00:00"),
     *                         @OA\Property(property="updated_at", type="string", example="2025-02-01 12:00:00")
     *                     )
     *                 ),
     *                 @OA\Property(property="meta", type="object",
     *                     @OA\Property(property="pagination", type="object",
     *                         @OA\Property(property="total", type="integer", example=195),
     *                         @OA\Property(property="count", type="integer", example=10),
     *                         @OA\Property(property="per_page", type="integer", example=10),
     *                         @OA\Property(property="current_page", type="integer", example=1),
     *                         @OA\Property(property="total_pages", type="integer", example=20)
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error de validación."),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="search", type="array", @OA\Items(type="string", example="Debe ser una cadena de caracteres.")),
     *                 @OA\Property(property="perPage", type="array", @OA\Items(type="string", example="Debe ser un valor numérico.")),
     *                 @OA\Property(property="subregion_id", type="array", @OA\Items(type="string", example="No existe en la base de datos."))
     *             )
     *         )
     *     )
     * )
     */
    public function __invoke(CountryAllRequest $request)
    {
        try {
            $countryAllUseCase = new CountryAllUseCase($this->countryRepository);
            $countries = new CountryCollection($countryAllUseCase->__invoke($request));
            return $this->successResponse(
                $countries,
                'Los paises fueron consultados exitosamente.',
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
