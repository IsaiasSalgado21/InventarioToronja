<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\SupplierSeeder;
use Database\Seeders\ItemSeeder;
use Database\Seeders\PresentationSeeder;
use Database\Seeders\InventoryMovementSeeder;
use Database\Seeders\PriceHistorySeeder;
use Database\Seeders\StorageZoneSeeder;
use Database\Seeders\ItemLocationSeeder;
use Database\Seeders\InventorySeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
        {
            $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            SupplierSeeder::class,
            StorageZoneSeeder::class,
        ]);

        $this->call(ItemSeeder::class);

        // 3. Presentations necesita Items (stock_current se pone en 0)
        $this->call(PresentationSeeder::class);

        // 4. ¡El Seeder Clave! Simula "Recibir Stock"
        // Crea ItemLocations, crea Movements y actualiza stock_current
        $this->call(InventorySeeder::class);
        
        // 5. (Opcional) Simula un cambio de precio de venta
        $this->call(PriceHistorySeeder::class);
    }
}
