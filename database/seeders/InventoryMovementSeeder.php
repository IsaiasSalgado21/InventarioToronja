<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InventoryMovementSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('inventory_movements')->insert([
            [
                'presentation_id' => 1,
                'user_id' => 1,
                'type' => 'entry',
                'quantity' => 36,
                'notes' => 'Entrada inicial de stock'
            ],
            [
                'presentation_id' => 2,
                'user_id' => 2,
                'type' => 'exit',
                'quantity' => 5,
                'notes' => 'Venta de camisas'
            ]
        ]);
    }
}
