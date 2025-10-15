<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StorageZoneSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('storage_zones')->insert([
            [
                'name' => 'Zona A',
                'description' => 'Área principal de 4x4 metros',
                'dimension_x' => 4.00,
                'dimension_y' => 4.00,
                'capacity_m2' => 16.00
            ],
            [
                'name' => 'Zona B',
                'description' => 'Estante auxiliar de 1x2 metros',
                'dimension_x' => 1.00,
                'dimension_y' => 2.00,
                'capacity_m2' => 2.00
            ]
        ]);
    }
}
