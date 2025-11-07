<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
     public function run(): void
    {
        $suppliers = [
            [
                'name' => 'Proveedor A (Nacional)',
                'contact' => 'Juan Perez',
                'phone' => '5512345678',
                'email' => 'ventas@proveedora.com'
            ],
            [
                'name' => 'Proveedor B (Importación)',
                'contact' => 'Ana Garcia',
                'phone' => '8187654321',
                'email' => 'info@proveedorb.com'
            ],
            [
                'name' => 'Sublimación Express',
                'contact' => 'Carlos Ruiz',
                'phone' => '5523456789',
                'email' => 'ventas@sublimacionexpress.mx'
            ],
            [
                'name' => 'Textiles del Norte',
                'contact' => 'María Sánchez',
                'phone' => '8123456789',
                'email' => 'pedidos@textilesnorte.com'
            ],
            [
                'name' => 'Import Master',
                'contact' => 'Roberto Wong',
                'phone' => '3334567890',
                'email' => 'rwong@importmaster.com'
            ],
            [
                'name' => 'Vinilos y Más',
                'contact' => 'Laura Torres',
                'phone' => '5545678901',
                'email' => 'ventas@vinilosymas.com'
            ],
            [
                'name' => 'Global Supplies Co.',
                'contact' => 'John Smith',
                'phone' => '5556789012',
                'email' => 'sales@globalsupplies.com'
            ],
            [
                'name' => 'Insumos Gráficos',
                'contact' => 'Patricia Rendón',
                'phone' => '5567890123',
                'email' => 'ventas@insumosgraficos.mx'
            ],
            [
                'name' => 'Mayorista Digital',
                'contact' => 'Fernando Vega',
                'phone' => '5578901234',
                'email' => 'fvega@mayoristadigital.com'
            ],
            [
                'name' => 'Asian Imports S.A.',
                'contact' => 'Lucy Chen',
                'phone' => '5589012345',
                'email' => 'lucy@asianimports.com'
            ]
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}
