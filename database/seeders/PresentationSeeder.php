<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Presentation;
use App\Models\Item;

class PresentationSeeder extends Seeder
{
    public function run(): void
    {
        $items = Item::all()->keyBy('name');
        
        $presentations = [
            // Tazas
            [
                'item' => 'Taza Blanca 11oz',
                'presentations' => [
                    ['sku' => 'TAZ-BLA-11-A', 'description' => 'Taza 11oz Calidad A (Caja 36 pzas)', 'archetype' => 'Clásica 11oz', 'quality' => 'A', 'units' => 36, 'base_unit' => 'caja', 'min_stock' => 10, 'price' => 1800.00, 'm2' => 0.25],
                    ['sku' => 'TAZ-BLA-11-AA', 'description' => 'Taza 11oz Calidad AA (Caja 36 pzas)', 'archetype' => 'Clásica 11oz', 'quality' => 'AA', 'units' => 36, 'base_unit' => 'caja', 'min_stock' => 5, 'price' => 2200.00, 'm2' => 0.25],
                    ['sku' => 'TAZ-BLA-11-IND', 'description' => 'Taza 11oz Individual', 'archetype' => 'Clásica 11oz', 'quality' => 'A', 'units' => 1, 'base_unit' => 'pieza', 'min_stock' => 50, 'price' => 55.00, 'm2' => 0.01]
                ]
            ],
            // Taza Mágica
            [
                'item' => 'Taza Mágica',
                'presentations' => [
                    ['sku' => 'TAZ-MAG-11-A', 'description' => 'Taza Mágica 11oz (Caja 24 pzas)', 'archetype' => 'Mágica', 'quality' => 'A', 'units' => 24, 'base_unit' => 'caja', 'min_stock' => 5, 'price' => 2400.00, 'm2' => 0.25],
                    ['sku' => 'TAZ-MAG-11-IND', 'description' => 'Taza Mágica Individual', 'archetype' => 'Mágica', 'quality' => 'A', 'units' => 1, 'base_unit' => 'pieza', 'min_stock' => 30, 'price' => 110.00, 'm2' => 0.01]
                ]
            ],
            // Playeras
            [
                'item' => 'Playera Polo',
                'presentations' => [
                    ['sku' => 'POLO-NEG-CH', 'description' => 'Playera Polo Negra CH', 'archetype' => 'Yazbek Piqué', 'quality' => 'Premium', 'units' => 1, 'base_unit' => 'pieza', 'min_stock' => 20, 'price' => 250.00, 'm2' => 0.05],
                    ['sku' => 'POLO-NEG-M', 'description' => 'Playera Polo Negra M', 'archetype' => 'Yazbek Piqué', 'quality' => 'Premium', 'units' => 1, 'base_unit' => 'pieza', 'min_stock' => 20, 'price' => 250.00, 'm2' => 0.05],
                    ['sku' => 'POLO-NEG-G', 'description' => 'Playera Polo Negra G', 'archetype' => 'Yazbek Piqué', 'quality' => 'Premium', 'units' => 1, 'base_unit' => 'pieza', 'min_stock' => 20, 'price' => 250.00, 'm2' => 0.05],
                    ['sku' => 'POLO-BLA-PAQ', 'description' => 'Playera Polo Blanca (Pack 10)', 'archetype' => 'Yazbek Piqué', 'quality' => 'Premium', 'units' => 10, 'base_unit' => 'paquete', 'min_stock' => 5, 'price' => 2300.00, 'm2' => 0.5]
                ]
            ],
            // Viniles
            [
                'item' => 'Vinil Adhesivo Brillante',
                'presentations' => [
                    ['sku' => 'VIN-ADH-50', 'description' => 'Vinil Adhesivo 50cm x 50m', 'archetype' => 'Brillante', 'quality' => 'A', 'units' => 1, 'base_unit' => 'rollo', 'min_stock' => 5, 'price' => 850.00, 'm2' => 0.3],
                    ['sku' => 'VIN-ADH-100', 'description' => 'Vinil Adhesivo 100cm x 50m', 'archetype' => 'Brillante', 'quality' => 'A', 'units' => 1, 'base_unit' => 'rollo', 'min_stock' => 3, 'price' => 1600.00, 'm2' => 0.5]
                ]
            ],
            // Termos
            [
                'item' => 'Termo Metálico',
                'presentations' => [
                    ['sku' => 'TERM-500-A', 'description' => 'Termo 500ml (Caja 24 pzas)', 'archetype' => 'Metálico', 'quality' => 'A', 'units' => 24, 'base_unit' => 'caja', 'min_stock' => 3, 'price' => 3600.00, 'm2' => 0.4],
                    ['sku' => 'TERM-500-IND', 'description' => 'Termo 500ml Individual', 'archetype' => 'Metálico', 'quality' => 'A', 'units' => 1, 'base_unit' => 'pieza', 'min_stock' => 15, 'price' => 180.00, 'm2' => 0.02]
                ]
            ]
        ];

        foreach ($presentations as $itemGroup) {
            $item = $items[$itemGroup['item']];
            foreach ($itemGroup['presentations'] as $p) {
                Presentation::create([
                    'item_id' => $item->id,
                    'sku' => $p['sku'],
                    'description' => $p['description'],
                    'archetype' => $p['archetype'],
                    'quality' => $p['quality'],
                    'units_per_presentation' => $p['units'],
                    'base_unit' => $p['base_unit'],
                    'stock_current' => 0,
                    'stock_minimum' => $p['min_stock'],
                    'unit_price' => $p['price'],
                    'm2_per_unit' => $p['m2']
                ]);
            }
        }
    }
}