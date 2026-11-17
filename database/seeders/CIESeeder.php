<?php

namespace Database\Seeders;

use App\Models\CIEChapter;
use App\Models\CIECode;
use App\Models\CIEGroup;
use App\Models\CIESubgroup;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class CIESeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = public_path('json/cie.json');
        $jsonData = File::get($jsonPath);
        $cies = json_decode($jsonData, true);

         foreach ($cies as $cie) {
            $chapter = CIEChapter::firstOrCreate(
                [
                    'name' => trim($cie['capitulo']),
                ],
                [
                    'description' => trim($cie['capitulo']),
                    'settings' => (object) [
                        'number' => (int) $cie['num_capitulo'],
                    ],
                ]
            );

            $group = CIEGroup::firstOrCreate(
                [
                    'name' => trim($cie['agrupacion1']),
                ],
                [
                    'description' => trim($cie['agrupacion1']),
                    'settings' => (object) [],
                ]
            );

            $group->cie_chapter()->syncWithoutDetaching([$chapter->id]);

            $subgroup = CIESubgroup::firstOrCreate(
                [
                    'name' => trim($cie['agrupacion2']),
                ],
                [
                    'description' => trim($cie['agrupacion2']),
                    'settings' => (object) [
                        'code' => trim($cie['codigo_agrupacion2']),
                    ],
                ]
            );

            $subgroup->cie_group()->syncWithoutDetaching([$group->id]);

            $code = CIECode::firstOrCreate(
                [
                    'name' => trim($cie['nombre']),
                ],
                [
                    'description' => trim($cie['nombre']),
                    'settings' => (object) [
                        'code' => trim($cie['codigo']),
                    ],
                ]
            );
            $code->cie_subgroup()->sync([$subgroup->id]);
        }
    }
}
