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
            // Tazas
            ['name' => 'Taza Blanca 11oz', 'description' => 'Taza de cerámica blanca para sublimación.', 'category' => 'Tazas'],
            ['name' => 'Taza Mágica', 'description' => 'Taza que cambia de color con el calor.', 'category' => 'Tazas'],
            ['name' => 'Taza Premium 15oz', 'description' => 'Taza grande de alta calidad.', 'category' => 'Tazas'],
            
            // Textiles
            ['name' => 'Playera Polo', 'description' => 'Playera tipo polo de algodón.', 'category' => 'Textiles'],
            ['name' => 'Playera Cuello Redondo', 'description' => 'Playera básica 100% algodón.', 'category' => 'Textiles'],
            ['name' => 'Sudadera', 'description' => 'Sudadera con capucha.', 'category' => 'Textiles'],
            
            // Viniles
            ['name' => 'Vinil Adhesivo Brillante', 'description' => 'Rollo de vinil adhesivo permanente.', 'category' => 'Viniles'],
            ['name' => 'Vinil Textil', 'description' => 'Vinil especial para tela.', 'category' => 'Viniles'],
            ['name' => 'Vinil Reflectivo', 'description' => 'Vinil con acabado reflectante.', 'category' => 'Viniles'],
            
            // Termos
            ['name' => 'Termo Metálico', 'description' => 'Termo de acero inoxidable.', 'category' => 'Termos'],
            ['name' => 'Termo Deportivo', 'description' => 'Termo con boquilla deportiva.', 'category' => 'Termos'],
            
            // Gorras
            ['name' => 'Gorra 5 Paneles', 'description' => 'Gorra estilo 5 paneles para sublimación.', 'category' => 'Gorras'],
            ['name' => 'Gorra Trucker', 'description' => 'Gorra tipo camionero con malla.', 'category' => 'Gorras'],
            
            // Papelería
            ['name' => 'Libreta Personalizable', 'description' => 'Libreta con cubierta sublimable.', 'category' => 'Papelería'],
            ['name' => 'Mouse Pad', 'description' => 'Tapete para mouse sublimable.', 'category' => 'Papelería'],
            
            // Insumos de Impresión
            ['name' => 'Tinta Sublimación', 'description' => 'Tinta especial para sublimación.', 'category' => 'Insumos de Impresión'],
            ['name' => 'Papel Transfer', 'description' => 'Papel especial para transferencia.', 'category' => 'Insumos de Impresión'],
            
            // Plásticos
            ['name' => 'Llavero Acrílico', 'description' => 'Llavero de acrílico sublimable.', 'category' => 'Plásticos'],
            ['name' => 'Placa Decorativa', 'description' => 'Placa de acrílico para personalizar.', 'category' => 'Plásticos'],
            
            // Metales
            ['name' => 'Placa Metálica', 'description' => 'Placa de aluminio sublimable.', 'category' => 'Metales'],
            ['name' => 'Prendedor Metálico', 'description' => 'Pin metálico personalizable.', 'category' => 'Metales'],
            
            // Cristalería
            ['name' => 'Vaso de Cristal', 'description' => 'Vaso de cristal sublimable.', 'category' => 'Cristalería'],
            ['name' => 'Copa de Vino', 'description' => 'Copa de vino personalizable.', 'category' => 'Cristalería'],
            
            // Herramientas
            ['name' => 'Plancha Térmica', 'description' => 'Plancha para sublimación.', 'category' => 'Herramientas'],
            ['name' => 'Cortadora de Vinil', 'description' => 'Máquina de corte.', 'category' => 'Herramientas'],
            
            // Empaques
            ['name' => 'Caja para Taza', 'description' => 'Caja individual para taza.', 'category' => 'Empaques'],
            ['name' => 'Bolsa de Regalo', 'description' => 'Bolsa decorativa para regalo.', 'category' => 'Empaques']
        ];

        foreach ($items as $item) {
            Item::create([
                'name' => $item['name'],
                'description' => $item['description'],
                'category_id' => $categories[$item['category']]->id
            ]);
        }
    }
}