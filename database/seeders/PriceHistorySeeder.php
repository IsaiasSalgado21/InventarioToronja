<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PriceHistorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('price_histories')->insert([
            [
                'presentation_id' => 1,
                'old_price' => 23.00,
                'new_price' => 25.50
            ],
            [
                'presentation_id' => 2,
                'old_price' => 110.00,
                'new_price' => 120.00
            ]
        ]);
    }
}
