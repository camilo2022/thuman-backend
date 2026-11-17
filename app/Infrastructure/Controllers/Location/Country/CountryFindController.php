<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Location\Country;

use App\Application\LocationUseCase\CountryUseCase\CountryFindUseCase;
use App\Domain\Location\Country\Values\CountryId;
use App\Http\Requests\Location\Country\CountryFindRequest;
use App\Http\Resources\Location\Country\CountryResource;
use App\Infrastructure\Repositories\CountryRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class CountryFindController
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
     *     path="/Locations/Countries/Find/{countryId}",
     *     summary="Buscar un país",
     *     description="Devuelve un país por ID.",
     *     tags={"Locations - Countries"},
     *     @OA\Parameter(
     *         name="countryId",
     *         in="path",
     *         description="ID del país",
     *         required=false,
     *         @OA\Schema(type="integer", example=170)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="El país fue encontrado exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="country", type="object",
     *                     @OA\Property(property="id", type="integer", example=170),
     *                     @OA\Property(property="subregion_id", type="integer", example=5),
     *                     @OA\Property(property="name", type="string", example="Colombia"),
     *                     @OA\Property(property="iso3", type="string", example="COL"),
     *                     @OA\Property(property="iso2", type="string", example="CO"),
     *                     @OA\Property(property="numeric_code", type="string", example="170"),
     *                     @OA\Property(property="phone_code", type="string", example="+57"),
     *                     @OA\Property(property="currency", type="string", example="COP"),
     *                     @OA\Property(property="currency_name", type="string", example="Colombian Peso"),
     *                     @OA\Property(property="currency_symbol", type="string", example="$"),
     *                     @OA\Property(property="tld", type="string", example=".co"),
     *                     @OA\Property(property="native", type="string", example="Colombia"),
     *                     @OA\Property(property="nationality", type="string", example="Colombian"),
     *                     @OA\Property(property="latitude", type="string", example="4.5709"),
     *                     @OA\Property(property="longitude", type="string", example="-74.2973"),
     *                     @OA\Property(property="emoji", type="string", example="🇨🇴"),
     *                     @OA\Property(property="emojiU", type="string", example="U+1F1E8 U+1F1F4"),
     *                     @OA\Property(property="translations", type="object",
     *                         @OA\Property(property="es", type="string", example="Colombia"),
     *                         @OA\Property(property="fr", type="string", example="Colombie"),
     *                         @OA\Property(property="en", type="string", example="Colombia")
     *                     ),
     *                     @OA\Property(property="created_at", type="string", example="2025-01-01 12:00:00"),
     *                     @OA\Property(property="updated_at", type="string", example="2025-02-01 12:00:00")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="El país no existe.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="El país no existe.")
     *         )
     *     )
     * )
     */
    public function __invoke(CountryFindRequest $request, int|null $countryId = null)
    {
        try {
            $countryId = new CountryId($countryId);
            $countryFindUseCase = new CountryFindUseCase($this->countryRepository);
            $country = new CountryResource($countryFindUseCase->__invoke($request, $countryId));
            return $this->successResponse(
                $country,
                'El pais fue encontrado exitosamente.',
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
