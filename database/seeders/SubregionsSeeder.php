<?php

namespace Database\Seeders;

use App\Models\Subregion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class SubregionsSeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = public_path('json/subregions.json');
        $jsonData = File::get($jsonPath);
        $subregions = json_decode($jsonData, true);

        foreach (collect($subregions)->sortBy('id') as $subregion) {
            Subregion::create([
                'id' => $subregion['id'],
                'region_id' => $subregion['region_id'],
                'name' => $subregion['name'],
                'translations' => $subregion['translations'] ?? [],
                'settings' => (object) ['validations' => []]
            ]);
        }
    }
}
