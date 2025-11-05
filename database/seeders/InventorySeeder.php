<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Presentation;
use App\Models\StorageZone;
use App\Models\Supplier;
use App\Models\User;
use App\Models\ItemLocation;
use App\Models\InventoryMovement;

class InventorySeeder extends Seeder
{
    /**
     * Simula la recepción de stock (storeReceive).
     * Crea ItemLocations, Movements y actualiza stock_current.
     */
    public function run(): void
    {
        // Obtenemos datos maestros
        $presentation1 = Presentation::where('sku', 'TAZ-BLA-11-A')->first();
        $presentation2 = Presentation::where('sku', 'POLO-NEG-M')->first();
        
        $zoneTazas = StorageZone::where('name', 'Estante A (Tazas)')->first();
        $zoneTextil = StorageZone::where('name', 'Bodega Textil')->first();

        $supplierA = Supplier::where('name', 'Proveedor A (Nacional)')->first();
        $supplierB = Supplier::where('name', 'Proveedor B (Importación)')->first();

        $user = User::where('role', 'admin')->first();

        // --- Simulación 1: Recibir 20 cajas de Tazas A ---
        $this->receiveStock(
            $user, 
            $presentation1, 
            $zoneTazas, 
            $supplierA, 
            20, // quantity (20 cajas)
            1200.00, // total_cost (costo total de las 20 cajas)
            'Pedido inicial #001'
        );

        // --- Simulación 2: Recibir 50 playeras Polo M ---
        $this->receiveStock(
            $user, 
            $presentation2, 
            $zoneTextil, 
            $supplierB, 
            50, // quantity (50 piezas)
            7500.00, // total_cost (50 pzas * $150 c/u)
            'Pedido importación #001'
        );

        // --- Simulación 3: Recibir 10 cajas más de Tazas A (más caras) ---
        $this->receiveStock(
            $user, 
            $presentation1, 
            $zoneTazas, 
            $supplierA, 
            10, // quantity (10 cajas)
            1350.00, // total_cost (subió a $135 c/u)
            'Resurtido #002'
        );
    }

    /**
     * Función helper para simular InventoryController@storeReceive
     */
    private function receiveStock($user, $presentation, $zone, $supplier, $quantity, $total_cost, $notes)
    {
        if (!$presentation || !$zone || !$supplier || !$user) {
            $this->command->error("Faltan datos maestros para simular la recepción.");
            return;
        }

        try {
            DB::transaction(function () use ($user, $presentation, $zone, $supplier, $quantity, $total_cost, $notes) {
                
                // Calcular costo unitario
                $unit_cost = ($total_cost > 0 && $quantity > 0) ? $total_cost / $quantity : 0;
                
                // Calcular m2 (opcional)
                $m2PerUnit = $presentation->m2_per_unit ?? 0;
                $calculated_occupied_m2 = $quantity * $m2PerUnit;

                // 1. Crear o actualizar la ubicación
                $location = ItemLocation::firstOrCreate(
                    [
                        'presentation_id' => $presentation->id,
                        'storage_zone_id' => $zone->id,
                    ],
                    ['occupied_m2' => 0, 'stored_quantity' => 0, 'assigned_at' => now()]
                );

                $location->increment('stored_quantity', $quantity);
                $location->increment('occupied_m2', $calculated_occupied_m2);

                // 2. Actualizar el stock total de la presentación
                $presentation->increment('stock_current', $quantity);
                
                // 3. (Opcional pero recomendado) Actualizar el precio de venta si es la primera vez
                // if ($presentation->unit_price == 0) {
                //     $presentation->update(['unit_price' => $unit_cost * 1.5]); // ej. margen del 50%
                // }

                // 4. Registrar el movimiento
                InventoryMovement::create([
                    'presentation_id' => $presentation->id,
                    'user_id'         => $user->id,
                    'supplier_id'     => $supplier->id,
                    'type'            => 'entrada',
                    'quantity'        => $quantity,
                    'unit_cost'       => $unit_cost,
                    'notes'           => $notes,
                    'movement_date'   => now(),
                ]);
            });
        } catch (\Exception $e) {
            $this->command->error("Error al sembrar inventario: " . $e->getMessage());
        }
    }
}