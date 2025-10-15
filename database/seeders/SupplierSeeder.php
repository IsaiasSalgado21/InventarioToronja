<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('suppliers')->insert([
            [
                'name' => 'Sublimarte MX',
                'contact' => 'Luis Ramírez',
                'phone' => '555-110-2233',
                'email' => 'ventas@sublimarte.com',
                'address' => 'Av. Reforma 123, CDMX'
            ],
            [
                'name' => 'VinilPrint',
                'contact' => 'Ana Torres',
                'phone' => '555-334-5566',
                'email' => 'contacto@vinilprint.com',
                'address' => 'Calle Arte 45, Puebla'
            ],
            [
                'name' => 'Promocionales León',
                'contact' => 'Marcos Hernández',
                'phone' => '477-221-8899',
                'email' => 'ventas@promoleon.com',
                'address' => 'Zona Centro, León Gto.'
            ]
        ]);
    }
}
