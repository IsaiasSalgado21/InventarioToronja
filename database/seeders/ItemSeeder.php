<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\Category;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        // Obtenemos los IDs de las categorías que ya creamos
        $catTazas = Category::where('name', 'Tazas')->first()->id;
        $catTextiles = Category::where('name', 'Textiles')->first()->id;
        $catViniles = Category::where('name', 'Viniles')->first()->id;

        Item::create([
            'name' => 'Taza Blanca 11oz',
            'description' => 'Taza de cerámica blanca para sublimación.',
            'category_id' => $catTazas
        ]);
        
        Item::create([
            'name' => 'Playera Polo',
            'description' => 'Playera tipo polo de algodón.',
            'category_id' => $catTextiles
        ]);

        Item::create([
            'name' => 'Vinil Adhesivo Brillante',
            'description' => 'Rollo de vinil adhesivo permanente.',
            'category_id' => $catViniles
        ]);
    }
}