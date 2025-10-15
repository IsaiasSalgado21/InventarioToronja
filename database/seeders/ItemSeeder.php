<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('items')->insert([
            [
                'name' => 'Taza Blanca 11oz',
                'description' => 'Taza para sublimación de 11oz',
                'category_id' => 1,
                'supplier_id' => 1,
                'abc_class' => 'A',  
                'expiry_date' => null
            ],
            [
                'name' => 'Camisa Polo Negra',
                'description' => 'Camisa de algodón para bordado',
                'category_id' => 2,
                'supplier_id' => 3,
                'abc_class' => 'B',  
                'expiry_date' => null
            ],
            [
                'name' => 'Vinil Adhesivo',
                'description' => 'Vinil blanco adhesivo 50cm x 1m',
                'category_id' => 4,
                'supplier_id' => 2,
                'abc_class' => 'A', 
                'expiry_date' => null
            ]
        ]);
    }
}
