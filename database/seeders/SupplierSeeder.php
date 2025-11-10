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
                 'email' => 'ventas@proveedora.com',
                 'rfc' => 'PAJ880101AA1', 
                 'address' => 'Calle Falsa 123, Col. Centro, CDMX' 
             ],
             [
                 'name' => 'Proveedor B (Importación)',
                 'contact' => 'Ana Garcia',
                 'phone' => '8187654321',
                 'email' => 'info@proveedorb.com',
                 'rfc' => 'PGB990202BB2', 
                 'address' => 'Av. Roble 789, Col. Valle, Monterrey, NL' 
             ],
             [
                 'name' => 'Sublimación Express',
                 'contact' => 'Carlos Ruiz',
                 'phone' => '5523456789',
                 'email' => 'ventas@sublimacionexpress.mx',
                 'rfc' => 'SEX010303CC3', 
                 'address' => 'Insurgentes Sur 456, Col. Roma, CDMX' 
             ],
             [
                 'name' => 'Textiles del Norte',
                 'contact' => 'María Sánchez',
                 'phone' => '8123456789',
                 'email' => 'pedidos@textilesnorte.com',
                 'rfc' => 'TNO050404DD4', 
                 'address' => 'Av. Morones Prieto 111, Monterrey, NL' 
             ],
             [
                 'name' => 'Import Master',
                 'contact' => 'Roberto Wong',
                 'phone' => '3334567890',
                 'email' => 'rwong@importmaster.com',
                 'rfc' => 'IME100505EE5', 
                 'address' => 'Av. Américas 222, Guadalajara, JAL' 
             ],
             [
                 'name' => 'Vinilos y Más',
                 'contact' => 'Laura Torres',
                 'phone' => '5545678901',
                 'email' => 'ventas@vinilosymas.com',
                 'rfc' => 'VIM120606FF6', 
                 'address' => 'Eje Central 333, Col. Doctores, CDMX' 
             ],
            [
                'name' => 'Global Supplies Co.',
                'contact' => 'John Smith',
                'phone' => '5556789012',
                'email' => 'sales@globalsupplies.com',
                'address' => '123 International St, Los Angeles, CA, USA',
                'RFC' => 'GSC210707GG7'
                
            ],
            [
                'name' => 'Insumos Gráficos',
                'contact' => 'Patricia Rendón',
                'phone' => '5567890123',
                'email' => 'ventas@insumosgraficos.mx',
                'address' => 'Av. de la Industria 456, Col. Industrial, CDMX',
                'RFC' => 'IGM220808HH8'
            ],
            [
                'name' => 'Mayorista Digital',
                'contact' => 'Fernando Vega',
                'phone' => '5578901234',
                'email' => 'fvega@mayoristadigital.com',
                'address' => 'Blvd. Tecnológico 789, Col. Tecnológica, CDMX',
                'RFC' => 'MDI230909II9'
            ],
            [
                'name' => 'Asian Imports S.A.',
                'contact' => 'Lucy Chen',
                'phone' => '5589012345',
                'email' => 'lucy@asianimports.com',
                'address' => '456 Global Ave, San Francisco, CA, USA',
                'RFC' => 'AIS240101JJ0'
            ]
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}
