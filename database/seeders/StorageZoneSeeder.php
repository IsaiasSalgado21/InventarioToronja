<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StorageZone;

class StorageZoneSeeder extends Seeder
{
    public function run(): void
    {
        // El Observer calculará capacity_m2 automáticamente
        
        StorageZone::create([
            'name' => 'Estante A (Tazas)',
            'description' => 'Estante principal para tazas y cerámicos',
            'dimension_x' => 5,
            'dimension_y' => 1,
            'capacity_units' => 500 // Capacidad para 500 unidades/cajas
        ]);

        StorageZone::create([
            'name' => 'Bodega Textil',
            'description' => 'Area de almacenamiento para playeras y gorras',
            'dimension_x' => 10,
            'dimension_y' => 5,
            'capacity_units' => 2000 
        ]);

        StorageZone::create([
            'name' => 'Rack Viniles',
            'description' => 'Rack vertical para rollos de vinil',
            'dimension_x' => 2,
            'dimension_y' => 2,
            'capacity_units' => 300 // Capacidad para 300 rollos
        ]);
    }
}