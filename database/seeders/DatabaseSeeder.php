<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(ItemsAndSubitemsSeeder::class);
        $this->call(RegionsSeeder::class);
        $this->call(SubregionsSeeder::class);
        $this->call(CountriesSeeder::class);
        $this->call(DepartamentsSeeder::class);
        $this->call(CitiesSeeder::class);
        $this->call(CIESeeder::class);
        $this->call(SuperadminSeeder::class);
    }
}
