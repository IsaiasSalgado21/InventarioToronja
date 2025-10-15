<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categories')->insert([
            ['name' => 'Tazas', 'description' => 'Tazas personalizadas y de colección'],
            ['name' => 'Camisas', 'description' => 'Camisas estampadas y bordadas'],
            ['name' => 'Tarjetas', 'description' => 'Tarjetas de presentación y etiquetas'],
            ['name' => 'Viniles', 'description' => 'Viniles decorativos y adhesivos'],
            ['name' => 'Termos', 'description' => 'Termos metálicos y de plástico'],
            ['name' => 'Cordones', 'description' => 'Lanyards personalizados'],
        ]);
    }
}
