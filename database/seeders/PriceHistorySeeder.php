<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Presentation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PriceHistorySeeder extends Seeder
{
    /**
     * Simula un cambio manual del precio de venta (unit_price).
     * Esto disparará el PresentationObserver para crear el registro de historial.
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

        // Obtenemos el precio antiguo y calculamos uno nuevo
        $old_price = $presentation->unit_price;
        $new_price = $old_price * 1.10; // Subir el precio de venta un 10%

        // Mostramos en consola
        $this->command->info("Cambiando precio de {$presentation->sku} de {$old_price} a {$new_price}...");

        // Actualizamos la presentación
        // ¡El OBSERVER se encargará de crear el registro en price_histories!
        $presentation->update([
            'unit_price' => $new_price
        ]);
    }
}