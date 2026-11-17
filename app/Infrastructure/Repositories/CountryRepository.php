<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Location\Country\Contracts\CountryContract;
use App\Models\Country as CountryModel;
use App\Domain\Location\Country\Country;
use App\Domain\Location\Country\Values\CountryCreatedAt;
use App\Domain\Location\Country\Values\CountryCurrency;
use App\Domain\Location\Country\Values\CountryCurrencyName;
use App\Domain\Location\Country\Values\CountryCurrencySymbol;
use App\Domain\Location\Country\Values\CountryEmoji;
use App\Domain\Location\Country\Values\CountryEmojiU;
use App\Domain\Location\Country\Values\CountryId;
use App\Domain\Location\Country\Values\CountryIso2;
use App\Domain\Location\Country\Values\CountryIso3;
use App\Domain\Location\Country\Values\CountryLatitude;
use App\Domain\Location\Country\Values\CountryLongitude;
use App\Domain\Location\Country\Values\CountryNacionality;
use App\Domain\Location\Country\Values\CountryName;
use App\Domain\Location\Country\Values\CountryNative;
use App\Domain\Location\Country\Values\CountryNumericCode;
use App\Domain\Location\Country\Values\CountryPhoneCode;
use App\Domain\Location\Country\Values\CountrySettings;
use App\Domain\Location\Country\Values\CountrySubregionId;
use App\Domain\Location\Country\Values\CountryTld;
use App\Domain\Location\Country\Values\CountryTranslations;
use App\Domain\Location\Country\Values\CountryUpdatedAt;
use App\Http\Requests\Location\Country\CountryAllRequest;
use App\Http\Requests\Location\Country\CountryFindRequest;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class CountryRepository implements CountryContract
{
    private $countryModel;

    public function __construct()
    {
        $this->countryModel = new CountryModel;
    }

    public function all(string|null $search = null, int|null $perPage, CountryAllRequest $request): LengthAwarePaginator
    {
        try {
            $countries = $this->countryModel
                ->with(['subregion', 'departaments'])
                ->when($search, fn($query) => $query->search($search))
                ->when($request->filled('subregion_id'), fn($query) => $query->where('subregion_id', $request->input('subregion_id')))
                ->orderBy('name', 'asc');

            return $countries->paginate($perPage ?? $countries->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener los paises: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener los paises: ' . $e->getMessage());
        }
    }

    public function find(CountryFindRequest $request, CountryId $countryId): ?Country
    {
        try {
            $country = $this->countryModel
                ->findOrFail($countryId->value());

            return $this->mapToEntity($country);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El pais no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el pais: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el pais: ' . $e->getMessage());
        }
    }

    public function update(Country $country, CountryId $countryId): Country
    {
        try {
            $model = tap($this->countryModel->findOrFail($countryId->value()))
                ->update($this->mapToModelData($country))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El pais no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar el pais: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar el pais: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): Country
    {
        return new Country(
            new CountryId($model->id),
            new CountrySubregionId($model->subregion_id),
            new CountryName($model->name),
            new CountryIso3($model->iso3),
            new CountryIso2($model->iso2),
            new CountryNumericCode($model->numeric_code),
            new CountryPhoneCode($model->phone_code),
            new CountryCurrency($model->currency),
            new CountryCurrencyName($model->currency_name),
            new CountryCurrencySymbol($model->currency_symbol),
            new CountryTld($model->tld),
            new CountryNative($model->native),
            new CountryNacionality($model->nationality),
            new CountryLatitude($model->latitude),
            new CountryLongitude($model->longitude),
            new CountryEmoji($model->emoji),
            new CountryEmojiU($model->emojiU),
            new CountryTranslations($model->translations),
            new CountrySettings($model->settings),
            new CountryCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new CountryUpdatedAt($model->updated_at?->format('Y-m-d H:i:s'))
        );
    }

    private function mapToModelData(Country $country): array
    {
        $array = [];

        if ($country->settings()->value()) {
            $array['settings'] = (object) [
                'validations' => $country->settings()->validations()
            ];
        }

        return $array;
    }
}
