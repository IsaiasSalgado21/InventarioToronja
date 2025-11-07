<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StorageZone;

class StorageZoneSeeder extends Seeder
{
    public function run(): void
    {
        // El Observer calculará capacity_m2 automáticamente
        $zones = [
            [
                'name' => 'Estante A (Tazas)',
                'description' => 'Estante principal para tazas y cerámicos',
                'dimension_x' => 5,
                'dimension_y' => 1,
                'capacity_units' => 500
            ],
            [
                'name' => 'Estante B (Tazas)',
                'description' => 'Estante secundario para tazas especiales',
                'dimension_x' => 4,
                'dimension_y' => 1,
                'capacity_units' => 400
            ],
            [
                'name' => 'Bodega Textil Principal',
                'description' => 'Área principal de almacenamiento textil',
                'dimension_x' => 10,
                'dimension_y' => 5,
                'capacity_units' => 2000
            ],
            [
                'name' => 'Bodega Textil Secundaria',
                'description' => 'Área para excedentes textiles',
                'dimension_x' => 8,
                'dimension_y' => 4,
                'capacity_units' => 1500
            ],
            [
                'name' => 'Rack Viniles A',
                'description' => 'Rack principal para viniles adhesivos',
                'dimension_x' => 2,
                'dimension_y' => 2,
                'capacity_units' => 300
            ],
            [
                'name' => 'Rack Viniles B',
                'description' => 'Rack para viniles especiales',
                'dimension_x' => 2,
                'dimension_y' => 2,
                'capacity_units' => 200
            ],
            [
                'name' => 'Almacén Termos',
                'description' => 'Área dedicada a termos y botellas',
                'dimension_x' => 6,
                'dimension_y' => 2,
                'capacity_units' => 800
            ],
            [
                'name' => 'Zona Empaques',
                'description' => 'Área para material de empaque',
                'dimension_x' => 4,
                'dimension_y' => 3,
                'capacity_units' => 1000
            ],
            [
                'name' => 'Insumos Generales',
                'description' => 'Área para insumos diversos',
                'dimension_x' => 5,
                'dimension_y' => 3,
                'capacity_units' => 600
            ],
            [
                'name' => 'Área Temporal',
                'description' => 'Zona para recepción y preparación',
                'dimension_x' => 4,
                'dimension_y' => 4,
                'capacity_units' => 400
            ]
        ];

        foreach ($zones as $zone) {
            StorageZone::create($zone);
        }
    }
}