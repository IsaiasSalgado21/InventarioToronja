<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Presentation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PriceHistorySeeder extends Seeder
{
    /**
     * Simula cambios manuales del precio de venta (unit_price) a lo largo del tiempo.
     * Esto creará varios registros en el historial de precios para visualizar la gráfica.
     */
    public function run(): void
    {
        // Buscamos la presentación de Tazas
        $presentation = Presentation::where('sku', 'TAZ-BLA-11-A')->first();
        if (!$presentation) {
            return;
        }

        // Buscamos un usuario para asignarle el cambio
        $user = User::where('role', 'admin')->first();
        if (!$user) {
            return;
        }

        // Simulamos que este usuario está logueado
        Auth::login($user);

        // Definimos los cambios de precio con sus fechas
        $priceChanges = [
            // 2024 - Historial del año anterior
            ['date' => '2024-01-15', 'price' => 1500.00],
            ['date' => '2024-02-01', 'price' => 1550.00],
            ['date' => '2024-03-10', 'price' => 1600.00],
            ['date' => '2024-04-15', 'price' => 1650.00],
            ['date' => '2024-05-01', 'price' => 1700.00],
            ['date' => '2024-06-15', 'price' => 1750.00],
            ['date' => '2024-07-01', 'price' => 1800.00],
            ['date' => '2024-08-15', 'price' => 1850.00],
            ['date' => '2024-09-01', 'price' => 1900.00],
            ['date' => '2024-10-15', 'price' => 1950.00],
            ['date' => '2024-11-01', 'price' => 2000.00],
            ['date' => '2024-12-15', 'price' => 1900.00], // Promoción navideña

            // 2025 - Año actual
            ['date' => '2025-01-15', 'price' => 2000.00], // Regreso a precio normal
            ['date' => '2025-02-01', 'price' => 2050.00],
            ['date' => '2025-03-10', 'price' => 2100.00],
            ['date' => '2025-04-01', 'price' => 2150.00],
            ['date' => '2025-05-15', 'price' => 2000.00], // Promoción especial
            ['date' => '2025-06-01', 'price' => 2200.00],
            ['date' => '2025-07-15', 'price' => 2250.00],
            ['date' => '2025-08-01', 'price' => 2300.00],
            ['date' => '2025-09-10', 'price' => 2350.00],
            ['date' => '2025-10-01', 'price' => 2400.00],
            ['date' => '2025-11-01', 'price' => 2450.00], // Último cambio
        ];

        foreach ($priceChanges as $change) {
            // Simulamos que el cambio se hizo en la fecha especificada
            $carbonDate = Carbon::parse($change['date']);
            
            // Mostramos en consola
            $this->command->info("Cambiando precio de {$presentation->sku} a {$change['price']} en fecha {$change['date']}...");

            // Actualizamos la presentación y forzamos la fecha del cambio
            DB::table('price_histories')->insert([
                'presentation_id' => $presentation->id,
                'old_price' => $presentation->unit_price,
                'new_price' => $change['price'],
                'user_id' => $user->id,
                'created_at' => $carbonDate,
                'updated_at' => $carbonDate,
            ]);

            $presentation->update([
                'unit_price' => $change['price']
            ]);
        }
    }
}