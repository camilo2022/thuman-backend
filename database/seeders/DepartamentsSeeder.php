<?php

namespace Database\Seeders;

use App\Models\Departament;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class DepartamentsSeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = public_path('json/departaments.json');
        $jsonData = File::get($jsonPath);
        $departaments = json_decode($jsonData, true);

        foreach (collect($departaments)->sortBy('id') as $departament) {
            Departament::create([
                'id' => $departament['id'],
                'country_id' => $departament['country_id'],
                'name' => $departament['name'],
                'state_code' => $departament['state_code'],
                'type' => $departament['type'],
                'latitude' => $departament['latitude'],
                'longitude' => $departament['longitude'],
                'settings' => (object) ['validations' => []]
            ]);
        }
    }
}
