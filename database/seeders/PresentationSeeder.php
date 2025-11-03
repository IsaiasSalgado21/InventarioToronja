<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PresentationSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('presentations')->insert([
            [
                'item_id' => 1,
                'sku' => 'TAZ-BLA-11OZ',
                'description' => 'Caja con 36 tazas blancas',
                'archetype' => 'Taza',
                'quality' => 'Estándar',
                'units_per_presentation' => 36,
                'stock_current' => 72,
                'stock_minimum' => 12,
                'unit_price' => 25.50
            ],
            [
                'item_id' => 2,
                'sku' => 'CAM-NEG-POLO',
                'description' => 'Camisa negra talla M',
                'archetype' => 'Camisa',
                'quality' => 'Premium',
                'units_per_presentation' => 1,
                'stock_current' => 50,
                'stock_minimum' => 10,
                'unit_price' => 120.00
            ],
            [
                'item_id' => 3,
                'sku' => 'VIN-BLA-ADH',
                'description' => 'Rollo 50cm x 10m',
                'archetype' => 'Vinilo',
                'quality' => 'Estándar',
                'units_per_presentation' => 1,
                'stock_current' => 20,
                'stock_minimum' => 5,
                'unit_price' => 150.00
            ]
        ]);
    }
}
