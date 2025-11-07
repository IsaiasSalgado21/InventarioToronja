<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Category;

class CategorySeeder extends Seeder
{
   public function run(): void
    {
        $categories = [
            ['name' => 'Tazas', 'description' => 'Tazas para sublimación y vinil'],
            ['name' => 'Textiles', 'description' => 'Playeras, gorras y otros textiles'],
            ['name' => 'Viniles', 'description' => 'Vinil adhesivo, vinil textil, etc.'],
            ['name' => 'Insumos de Impresión', 'description' => 'Tintas, papeles, cintas térmicas'],
            ['name' => 'Termos', 'description' => 'Termos metálicos y plásticos para sublimación'],
            ['name' => 'Gorras', 'description' => 'Gorras para sublimación y bordado'],
            ['name' => 'Papelería', 'description' => 'Artículos de papelería personalizable'],
            ['name' => 'Plásticos', 'description' => 'Productos plásticos para sublimación'],
            ['name' => 'Metales', 'description' => 'Placas y productos metálicos'],
            ['name' => 'Cristalería', 'description' => 'Vasos y artículos de cristal'],
            ['name' => 'Herramientas', 'description' => 'Herramientas y equipos de producción'],
            ['name' => 'Empaques', 'description' => 'Material de empaque y presentación']
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
