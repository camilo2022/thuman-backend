<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class CountriesSeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = public_path('json/countries.json');
        $jsonData = File::get($jsonPath);
        $countries = json_decode($jsonData, true);

        foreach (collect($countries)->sortBy('id') as $country) {
            Country::create([
                'id' => $country['id'],
                'subregion_id' => $country['subregion_id'],
                'name' => $country['name'],
                'iso3' => $country['iso3'],
                'iso2' => $country['iso2'],
                'numeric_code' => $country['numeric_code'],
                'phone_code' => $country['phonecode'],
                'currency' => $country['currency'],
                'currency_name' => $country['currency_name'],
                'currency_symbol' => $country['currency_symbol'],
                'tld' => $country['tld'],
                'native' => $country['native'],
                'nationality' => $country['nationality'],
                'latitude' => $country['latitude'],
                'longitude' => $country['longitude'],
                'emoji' => $country['emoji'],
                'emojiU' => $country['emojiU'],
                'translations' => $country['translations'] ?? [],
                'settings' => $country['settings'] ?? (object) ['validations' => []]
            ]);
        }
    }
}
