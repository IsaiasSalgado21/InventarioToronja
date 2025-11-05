<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Category;

class CategorySeeder extends Seeder
{
   public function run(): void
    {
        Category::create(['name' => 'Tazas', 'description' => 'Tazas para sublimación y vinil']);
        Category::create(['name' => 'Textiles', 'description' => 'Playeras, gorras y otros textiles']);
        Category::create(['name' => 'Viniles', 'description' => 'Vinil adhesivo, vinil textil, etc.']);
        Category::create(['name' => 'Insumos de Impresión', 'description' => 'Tintas, papeles, cintas térmicas']);
    }
}
