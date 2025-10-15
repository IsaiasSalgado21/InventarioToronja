<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemLocationSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('item_locations')->insert([
            [
                'presentation_id' => 1,
                'storage_zone_id' => 1,  
                'occupied_m2' => 2.5,    
                'stored_quantity' => 36,
                'assigned_at' => now()
            ],
            [
                'presentation_id' => 2,
                'storage_zone_id' => 2,  
                'occupied_m2' => 1.2,   
                'stored_quantity' => 10,
                'assigned_at' => now()
            ]
        ]);
    }
}
