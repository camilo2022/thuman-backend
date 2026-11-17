<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class RegionsSeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = public_path('json/regions.json');
        $jsonData = File::get($jsonPath);
        $regions = json_decode($jsonData, true);

        foreach (collect($regions)->sortBy('id') as $region) {
            Region::create([
                'id' => $region['id'],
                'name' => $region['name'],
                'translations' => $region['translations'] ?? [],
                'settings' => (object) ['validations' => []]
            ]);
        }
    }
}
