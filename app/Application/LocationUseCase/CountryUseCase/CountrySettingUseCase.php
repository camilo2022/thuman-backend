<?php

declare(strict_types=1);

namespace App\Application\LocationUseCase\CountryUseCase;

use App\Domain\Location\Country\Contracts\CountryContract;
use App\Domain\Location\Country\Country;
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
use App\Http\Requests\Location\Country\CountrySettingRequest;

final class CountrySettingUseCase
{
    private $countryContract;

    public function __construct(CountryContract $countryContract)
    {
        $this->countryContract = $countryContract;
    }

    public function __invoke(CountrySettingRequest $request, CountryId $regionId): ?Country
    {
        return $this->countryContract->update(
            new Country(
                new CountryId($request->input('id')),
                new CountrySubregionId($request->input('subregion_id')),
                new CountryName($request->input('name')),
                new CountryIso3($request->input('iso3')),
                new CountryIso2($request->input('iso2')),
                new CountryNumericCode($request->input('numeric_code')),
                new CountryPhoneCode($request->input('phone_code')),
                new CountryCurrency($request->input('currency')),
                new CountryCurrencyName($request->input('currency_name')),
                new CountryCurrencySymbol($request->input('currency_symbol')),
                new CountryTld($request->input('tld')),
                new CountryNative($request->input('native')),
                new CountryNacionality($request->input('nationality')),
                new CountryLatitude($request->input('latitude')),
                new CountryLongitude($request->input('longitude')),
                new CountryEmoji($request->input('emoji')),
                new CountryEmojiU($request->input('emojiU')),
                new CountryTranslations($request->input('translations')),
                new CountrySettings($request->input('settings')),
            ),
            $regionId
        );
    }
}
