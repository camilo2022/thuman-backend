<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Location\Country;

use App\Application\LocationUseCase\CountryUseCase\CountrySettingUseCase;
use App\Domain\Location\Country\Values\CountryId;
use App\Http\Requests\Location\Country\CountrySettingRequest;
use App\Http\Resources\Location\Country\CountryResource;
use App\Infrastructure\Repositories\CountryRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class CountrySettingController
{
    use ApiResponser;
    use ApiMessage;
    private $countryRepository;

    public function __construct(CountryRepository $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }

    public function __invoke(CountrySettingRequest $request, int $countryId)
    {
        try {
            $countryId = new CountryId($countryId ?? null);
            $countrySettingUseCase = new CountrySettingUseCase($this->countryRepository);
            $country = new CountryResource($countrySettingUseCase->__invoke($request, $countryId));
            return $this->successResponse(
                $country,
                'La configuración del país fue realizada exitosamente.',
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
