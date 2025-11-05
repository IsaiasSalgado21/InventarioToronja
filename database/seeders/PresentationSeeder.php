<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Presentation;
use App\Models\Item;

class PresentationSeeder extends Seeder
{
    public function run(): void
    {
        // Obtenemos los IDs de los Items
        $itemTaza = Item::where('name', 'Taza Blanca 11oz')->first()->id;
        $itemPolo = Item::where('name', 'Playera Polo')->first()->id;

        // --- Presentaciones para TAZA ---
        Presentation::create([
            'item_id' => $itemTaza,
            'sku' => 'TAZ-BLA-11-A',
            'description' => 'Taza 11oz Calidad A (Caja 36 pzas)',
            'archetype' => 'Clásica 11oz',
            'quality' => 'A',
            'units_per_presentation' => 36, // La unidad base es "caja"
            'base_unit' => 'caja',
            'stock_current' => 0, // Inicia en 0
            'stock_minimum' => 10, // 10 cajas
            'unit_price' => 1800.00, // Precio de Venta de la caja
            'm2_per_unit' => 0.25 // 1 caja ocupa 0.25 m²
        ]);
        
        Presentation::create([
            'item_id' => $itemTaza,
            'sku' => 'TAZ-BLA-11-AA',
            'description' => 'Taza 11oz Calidad AA (Caja 36 pzas)',
            'archetype' => 'Clásica 11oz',
            'quality' => 'AA',
            'units_per_presentation' => 36,
            'base_unit' => 'caja',
            'stock_current' => 0,
            'stock_minimum' => 5,
            'unit_price' => 2200.00,
            'm2_per_unit' => 0.25
        ]);

        // --- Presentaciones para PLAYERA ---
        Presentation::create([
            'item_id' => $itemPolo,
            'sku' => 'POLO-NEG-M',
            'description' => 'Playera Polo Negra Talla M',
            'archetype' => 'Yazbek Piqué',
            'quality' => 'Premium',
            'units_per_presentation' => 1,
            'base_unit' => 'pieza',
            'stock_current' => 0,
            'stock_minimum' => 20,
            'unit_price' => 250.00,
            'm2_per_unit' => 0.05 // 1 playera doblada
        ]);
    }
}