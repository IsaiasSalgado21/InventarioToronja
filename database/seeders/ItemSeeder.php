<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\Category;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        // Obtenemos todas las categorías
        $categories = Category::all()->keyBy('name');
        
        $items = [
            // Tazas (B = Venta media, A = Premium, C = Consumible)
            ['name' => 'Taza Blanca 11oz', 'description' => 'Taza de cerámica blanca para sublimación.', 'category' => 'Tazas', 'abc_class' => 'B', 'expiry_date' => null],
            ['name' => 'Taza Mágica', 'description' => 'Taza que cambia de color con el calor.', 'category' => 'Tazas', 'abc_class' => 'B', 'expiry_date' => null],
            ['name' => 'Taza Premium 15oz', 'description' => 'Taza grande de alta calidad.', 'category' => 'Tazas', 'abc_class' => 'A', 'expiry_date' => null],
            
            // Textiles (B = Venta media)
            ['name' => 'Playera Polo', 'description' => 'Playera tipo polo de algodón.', 'category' => 'Textiles', 'abc_class' => 'B', 'expiry_date' => null],
            ['name' => 'Playera Cuello Redondo', 'description' => 'Playera básica 100% algodón.', 'category' => 'Textiles', 'abc_class' => 'B', 'expiry_date' => null],
            ['name' => 'Sudadera', 'description' => 'Sudadera con capucha.', 'category' => 'Textiles', 'abc_class' => 'B', 'expiry_date' => null],
            
            // Viniles (C = Consumible, alta rotación)
            ['name' => 'Vinil Adhesivo Brillante', 'description' => 'Rollo de vinil adhesivo permanente.', 'category' => 'Viniles', 'abc_class' => 'C', 'expiry_date' => now()->addYears(2)],
            ['name' => 'Vinil Textil', 'description' => 'Vinil especial para tela.', 'category' => 'Viniles', 'abc_class' => 'C', 'expiry_date' => now()->addYears(2)],
            ['name' => 'Vinil Reflectivo', 'description' => 'Vinil con acabado reflectante.', 'category' => 'Viniles', 'abc_class' => 'C', 'expiry_date' => now()->addYears(2)],
            
            // Termos (B = Venta media)
            ['name' => 'Termo Metálico', 'description' => 'Termo de acero inoxidable.', 'category' => 'Termos', 'abc_class' => 'B', 'expiry_date' => null],
            ['name' => 'Termo Deportivo', 'description' => 'Termo con boquilla deportiva.', 'category' => 'Termos', 'abc_class' => 'B', 'expiry_date' => null],
            
            // Gorras (B = Venta media)
            ['name' => 'Gorra 5 Paneles', 'description' => 'Gorra estilo 5 paneles para sublimación.', 'category' => 'Gorras', 'abc_class' => 'B', 'expiry_date' => null],
            ['name' => 'Gorra Trucker', 'description' => 'Gorra tipo camionero con malla.', 'category' => 'Gorras', 'abc_class' => 'B', 'expiry_date' => null],
            
            // Papelería (C = Consumible/Bajo costo)
            ['name' => 'Libreta Personalizable', 'description' => 'Libreta con cubierta sublimable.', 'category' => 'Papelería', 'abc_class' => 'C', 'expiry_date' => null],
            ['name' => 'Mouse Pad', 'description' => 'Tapete para mouse sublimable.', 'category' => 'Papelería', 'abc_class' => 'C', 'expiry_date' => null],
            
            // Insumos de Impresión (C = Consumible, alta rotación, caduca)
            ['name' => 'Tinta Sublimación', 'description' => 'Tinta especial para sublimación.', 'category' => 'Insumos de Impresión', 'abc_class' => 'C', 'expiry_date' => now()->addYear()],
            ['name' => 'Papel Transfer', 'description' => 'Papel especial para transferencia.', 'category' => 'Insumos de Impresión', 'abc_class' => 'C', 'expiry_date' => now()->addYears(2)],
            
            // Plásticos (C = Bajo costo)
            ['name' => 'Llavero Acrílico', 'description' => 'Llavero de acrílico sublimable.', 'category' => 'Plásticos', 'abc_class' => 'C', 'expiry_date' => null],
            ['name' => 'Placa Decorativa', 'description' => 'Placa de acrílico para personalizar.', 'category' => 'Plásticos', 'abc_class' => 'C', 'expiry_date' => null],
            
            // Metales (B = Costo medio)
            ['name' => 'Placa Metálica', 'description' => 'Placa de aluminio sublimable.', 'category' => 'Metales', 'abc_class' => 'B', 'expiry_date' => null],
            ['name' => 'Prendedor Metálico', 'description' => 'Pin metálico personalizable.', 'category' => 'Metales', 'abc_class' => 'B', 'expiry_date' => null],
            
            // Cristalería (B = Costo medio, frágil)
            ['name' => 'Vaso de Cristal', 'description' => 'Vaso de cristal sublimable.', 'category' => 'Cristalería', 'abc_class' => 'B', 'expiry_date' => null],
            ['name' => 'Copa de Vino', 'description' => 'Copa de vino personalizable.', 'category' => 'Cristalería', 'abc_class' => 'B', 'expiry_date' => null],
            
            // Herramientas (A = Costo Alto, no rota)
            ['name' => 'Plancha Térmica', 'description' => 'Plancha para sublimación.', 'category' => 'Herramientas', 'abc_class' => 'A', 'expiry_date' => null],
            ['name' => 'Cortadora de Vinil', 'description' => 'Máquina de corte.', 'category' => 'Herramientas', 'abc_class' => 'A', 'expiry_date' => null],
            
            // Empaques (C = Consumible, bajo costo)
            ['name' => 'Caja para Taza', 'description' => 'Caja individual para taza.', 'category' => 'Empaques', 'abc_class' => 'C', 'expiry_date' => null],
            ['name' => 'Bolsa de Regalo', 'description' => 'Bolsa decorativa para regalo.', 'category' => 'Empaques', 'abc_class' => 'C', 'expiry_date' => null]
        ];

        foreach ($items as $item) {
            // Verificar que la categoría exista antes de intentar crear el item
            if (isset($categories[$item['category']])) {
                Item::create([
                    'name' => $item['name'],
                    'description' => $item['description'],
                    'category_id' => $categories[$item['category']]->id,
                    'abc_class' => $item['abc_class'],   // <-- AÑADIDO
                    'expiry_date' => $item['expiry_date']  // <-- AÑADIDO
                ]);
            } else {
                // Si la categoría no existe, se lo notifica en la consola
                $this->command->warn("Categoría '{$item['category']}' no encontrada para el item '{$item['name']}'. Omitiendo.");
            }
        }
    }
}