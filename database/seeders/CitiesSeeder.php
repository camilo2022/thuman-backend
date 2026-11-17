<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class CitiesSeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = public_path('json/cities.json');
        $jsonData = File::get($jsonPath);
        $cities = json_decode($jsonData, true);

        foreach (collect($cities)->sortBy('id') as $city) {
            City::create([
                'id' => $city['id'],
                'departament_id' => $city['state_id'],
                'name' => $city['name'],
                'latitude' => $city['latitude'],
                'longitude' => $city['longitude'],
                'settings' => (object) ['validations' => []]
            ]);
        }
    }
}
