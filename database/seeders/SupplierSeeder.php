<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
     public function run(): void
    {
        Supplier::create([
            'name' => 'Proveedor A (Nacional)',
            'contact' => 'Juan Perez',
            'phone' => '5512345678',
            'email' => 'ventas@proveedora.com'
        ]);
        Supplier::create([
            'name' => 'Proveedor B (Importación)',
            'contact' => 'Ana Garcia',
            'phone' => '8187654321',
            'email' => 'info@proveedorb.com'
        ]);
    }
}
