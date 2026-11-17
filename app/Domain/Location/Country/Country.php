<?php

declare(strict_types=1);

namespace App\Domain\Location\Country;

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

final class Country
{
    private $id;
    private $subregionId;
    private $name;
    private $iso3;
    private $iso2;
    private $numericCode;
    private $phoneCode;
    private $currency;
    private $currencyName;
    private $currencySymbol;
    private $tld;
    private $native;
    private $nationality;
    private $latitude;
    private $longitude;
    private $emoji;
    private $emojiU;
    private $translations;
    private $settings;
    private $createdAt;
    private $updatedAt;

    public function __construct(
        CountryId $id,
        CountrySubregionId $subregionId,
        CountryName $name,
        CountryIso3 $iso3,
        CountryIso2 $iso2,
        CountryNumericCode $numericCode,
        CountryPhoneCode $phoneCode,
        CountryCurrency $currency,
        CountryCurrencyName $currencyName,
        CountryCurrencySymbol $currencySymbol,
        CountryTld $tld,
        CountryNative $native,
        CountryNacionality $nationality,
        CountryLatitude $latitude,
        CountryLongitude $longitude,
        CountryEmoji $emoji,
        CountryEmojiU $emojiU,
        CountryTranslations $translations,
        CountrySettings $settings,
        ?CountryCreatedAt $createdAt = null,
        ?CountryUpdatedAt $updatedAt = null
    )
    {
        $this->id = $id;
        $this->subregionId = $subregionId;
        $this->name = $name;
        $this->iso3 = $iso3;
        $this->iso2 = $iso2;
        $this->numericCode = $numericCode;
        $this->phoneCode = $phoneCode;
        $this->currency = $currency;
        $this->currencyName = $currencyName;
        $this->currencySymbol = $currencySymbol;
        $this->tld = $tld;
        $this->native = $native;
        $this->nationality = $nationality;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->emoji = $emoji;
        $this->emojiU = $emojiU;
        $this->translations = $translations;
        $this->settings = $settings;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public function id(): ?CountryId
    {
        return $this->id;
    }

    public function subregionId(): CountrySubregionId
    {
        return $this->subregionId;
    }

    public function name(): CountryName
    {
        return $this->name;
    }

    public function iso3(): CountryIso3
    {
        return $this->iso3;
    }

    public function iso2(): CountryIso2
    {
        return $this->iso2;
    }

    public function numericCode(): CountryNumericCode
    {
        return $this->numericCode;
    }

    public function phoneCode(): CountryPhoneCode
    {
        return $this->phoneCode;
    }

    public function currency(): CountryCurrency
    {
        return $this->currency;
    }

    public function currencyName(): CountryCurrencyName
    {
        return $this->currencyName;
    }

    public function currencySymbol(): CountryCurrencySymbol
    {
        return $this->currencySymbol;
    }

    public function tld(): CountryTld
    {
        return $this->tld;
    }

    public function native(): CountryNative
    {
        return $this->native;
    }

    public function nationality(): CountryNacionality
    {
        return $this->nationality;
    }

    public function latitude(): CountryLatitude
    {
        return $this->latitude;
    }

    public function longitude(): CountryLongitude
    {
        return $this->longitude;
    }

    public function emoji(): CountryEmoji
    {
        return $this->emoji;
    }

    public function emojiU(): CountryEmojiU
    {
        return $this->emojiU;
    }

    public function translations(): CountryTranslations
    {
        return $this->translations;
    }

    public function settings(): CountrySettings
    {
        return $this->settings;
    }

    public function createdAt(): CountryCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): CountryUpdatedAt
    {
        return $this->updatedAt;
    }
}
