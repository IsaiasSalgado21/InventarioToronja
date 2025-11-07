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
        $user = User::where('role', 'admin')->first();
        
        // Proveedores
        $suppliers = Supplier::all()->keyBy('name');
        
        // Zonas de almacenamiento
        $zones = StorageZone::all()->keyBy('name');
        
        // Presentaciones frecuentes
        $presentations = [
            'tazas_a' => Presentation::where('sku', 'TAZ-BLA-11-A')->first(),
            'tazas_aa' => Presentation::where('sku', 'TAZ-BLA-11-AA')->first(),
            'tazas_ind' => Presentation::where('sku', 'TAZ-BLA-11-IND')->first(),
            'tazas_mag' => Presentation::where('sku', 'TAZ-MAG-11-A')->first(),
            'polo_m' => Presentation::where('sku', 'POLO-NEG-M')->first(),
            'polo_ch' => Presentation::where('sku', 'POLO-NEG-CH')->first(),
            'polo_pack' => Presentation::where('sku', 'POLO-BLA-PAQ')->first(),
            'vinil_50' => Presentation::where('sku', 'VIN-ADH-50')->first(),
            'termo_bulk' => Presentation::where('sku', 'TERM-500-A')->first(),
            'termo_ind' => Presentation::where('sku', 'TERM-500-IND')->first(),
        ];

        // Simulaciones de movimientos
        $movements = [
            // Enero 2025
            ['pres' => 'tazas_a', 'zone' => 'Estante A (Tazas)', 'supplier' => 'Proveedor A (Nacional)', 'qty' => 20, 'cost' => 1200.00, 'notes' => 'Pedido inicial #001-2025'],
            ['pres' => 'polo_m', 'zone' => 'Bodega Textil Principal', 'supplier' => 'Textiles del Norte', 'qty' => 50, 'cost' => 7500.00, 'notes' => 'Resurtido regular enero'],
            
            // Febrero 2025
            ['pres' => 'tazas_mag', 'zone' => 'Estante B (Tazas)', 'supplier' => 'Import Master', 'qty' => 15, 'cost' => 2250.00, 'notes' => 'Nuevos modelos mágicos'],
            ['pres' => 'vinil_50', 'zone' => 'Rack Viniles A', 'supplier' => 'Vinilos y Más', 'qty' => 30, 'cost' => 15000.00, 'notes' => 'Resurtido viniles adhesivos'],
            
            // Marzo 2025
            ['pres' => 'termo_bulk', 'zone' => 'Almacén Termos', 'supplier' => 'Global Supplies Co.', 'qty' => 10, 'cost' => 3000.00, 'notes' => 'Primera compra termos'],
            ['pres' => 'polo_pack', 'zone' => 'Bodega Textil Secundaria', 'supplier' => 'Textiles del Norte', 'qty' => 20, 'cost' => 35000.00, 'notes' => 'Paquetes promocionales'],
            
            // Abril 2025
            ['pres' => 'tazas_aa', 'zone' => 'Estante B (Tazas)', 'supplier' => 'Asian Imports S.A.', 'qty' => 25, 'cost' => 4500.00, 'notes' => 'Tazas premium importación'],
            ['pres' => 'termo_ind', 'zone' => 'Área Temporal', 'supplier' => 'Mayorista Digital', 'qty' => 100, 'cost' => 12000.00, 'notes' => 'Termos individuales oferta'],
            
            // Mayo 2025
            ['pres' => 'tazas_a', 'zone' => 'Estante A (Tazas)', 'supplier' => 'Proveedor A (Nacional)', 'qty' => 30, 'cost' => 1950.00, 'notes' => 'Resurtido mayo #025'],
            ['pres' => 'polo_ch', 'zone' => 'Bodega Textil Principal', 'supplier' => 'Textiles del Norte', 'qty' => 40, 'cost' => 6000.00, 'notes' => 'Playeras talla CH'],
            
            // Junio 2025
            ['pres' => 'vinil_50', 'zone' => 'Rack Viniles B', 'supplier' => 'Vinilos y Más', 'qty' => 25, 'cost' => 13750.00, 'notes' => 'Viniles especiales junio'],
            ['pres' => 'tazas_mag', 'zone' => 'Estante B (Tazas)', 'supplier' => 'Import Master', 'qty' => 20, 'cost' => 3200.00, 'notes' => 'Resurtido tazas mágicas'],
            
            // Julio 2025
            ['pres' => 'termo_bulk', 'zone' => 'Almacén Termos', 'supplier' => 'Global Supplies Co.', 'qty' => 15, 'cost' => 4800.00, 'notes' => 'Termos temporada verano'],
            ['pres' => 'polo_m', 'zone' => 'Bodega Textil Principal', 'supplier' => 'Textiles del Norte', 'qty' => 60, 'cost' => 9600.00, 'notes' => 'Resurtido playeras M'],
            
            // Agosto 2025
            ['pres' => 'tazas_ind', 'zone' => 'Área Temporal', 'supplier' => 'Proveedor A (Nacional)', 'qty' => 200, 'cost' => 8000.00, 'notes' => 'Tazas individuales agosto'],
            ['pres' => 'polo_pack', 'zone' => 'Bodega Textil Secundaria', 'supplier' => 'Textiles del Norte', 'qty' => 15, 'cost' => 27000.00, 'notes' => 'Paquetes regreso a clases'],
            
            // Septiembre 2025
            ['pres' => 'tazas_a', 'zone' => 'Estante A (Tazas)', 'supplier' => 'Proveedor A (Nacional)', 'qty' => 40, 'cost' => 2800.00, 'notes' => 'Preparación temporada alta'],
            ['pres' => 'termo_ind', 'zone' => 'Almacén Termos', 'supplier' => 'Mayorista Digital', 'qty' => 150, 'cost' => 19500.00, 'notes' => 'Termos individuales sept'],
            
            // Octubre 2025
            ['pres' => 'tazas_aa', 'zone' => 'Estante B (Tazas)', 'supplier' => 'Asian Imports S.A.', 'qty' => 30, 'cost' => 5700.00, 'notes' => 'Tazas premium octubre'],
            ['pres' => 'vinil_50', 'zone' => 'Rack Viniles A', 'supplier' => 'Vinilos y Más', 'qty' => 35, 'cost' => 19250.00, 'notes' => 'Viniles temporada alta'],
            
            // Noviembre 2025 (Actual)
            ['pres' => 'tazas_mag', 'zone' => 'Estante B (Tazas)', 'supplier' => 'Import Master', 'qty' => 25, 'cost' => 4250.00, 'notes' => 'Tazas mágicas navidad'],
            ['pres' => 'termo_bulk', 'zone' => 'Almacén Termos', 'supplier' => 'Global Supplies Co.', 'qty' => 20, 'cost' => 6800.00, 'notes' => 'Termos temporada invierno']
        ];

        // Ejecutar todas las simulaciones de entrada
        foreach ($movements as $mov) {
            $this->receiveStock(
                $user,
                $presentations[$mov['pres']],
                $zones[$mov['zone']],
                $suppliers[$mov['supplier']],
                $mov['qty'],
                $mov['cost'],
                $mov['notes']
            );
        }

        // --- Ahora añadimos movimientos de salida (ventas) y merma ---
        $sales = [
            ['pres' => 'tazas_ind', 'zone' => 'Área Temporal', 'qty' => 10, 'notes' => 'Venta retail #S-001'],
            ['pres' => 'polo_m', 'zone' => 'Bodega Textil Principal', 'qty' => 8, 'notes' => 'Venta online #S-002'],
            ['pres' => 'tazas_a', 'zone' => 'Estante A (Tazas)', 'qty' => 5, 'notes' => 'Venta local #S-003'],
            ['pres' => 'polo_ch', 'zone' => 'Bodega Textil Principal', 'qty' => 12, 'notes' => 'Venta tienda #S-004'],
            ['pres' => 'vinil_50', 'zone' => 'Rack Viniles A', 'qty' => 3, 'notes' => 'Venta por rollo #S-005'],
            ['pres' => 'tazas_mag', 'zone' => 'Estante B (Tazas)', 'qty' => 7, 'notes' => 'Venta especial #S-006'],
            ['pres' => 'termo_ind', 'zone' => 'Almacén Termos', 'qty' => 20, 'notes' => 'Venta mayorista #S-007'],
            ['pres' => 'polo_pack', 'zone' => 'Bodega Textil Secundaria', 'qty' => 2, 'notes' => 'Venta pack #S-008'],
            ['pres' => 'tazas_aa', 'zone' => 'Estante B (Tazas)', 'qty' => 4, 'notes' => 'Venta premium #S-009'],
            ['pres' => 'termo_bulk', 'zone' => 'Almacén Termos', 'qty' => 1, 'notes' => 'Venta caja termos #S-010']
        ];

        $wastes = [
            ['pres' => 'tazas_ind', 'zone' => 'Área Temporal', 'qty' => 2, 'notes' => 'Merma por rotura #M-001'],
            ['pres' => 'polo_m', 'zone' => 'Bodega Textil Principal', 'qty' => 1, 'notes' => 'Merma por mancha #M-002'],
            ['pres' => 'tazas_a', 'zone' => 'Estante A (Tazas)', 'qty' => 3, 'notes' => 'Merma control de calidad #M-003'],
            ['pres' => 'polo_ch', 'zone' => 'Bodega Textil Principal', 'qty' => 2, 'notes' => 'Merma por lote #M-004'],
            ['pres' => 'vinil_50', 'zone' => 'Rack Viniles A', 'qty' => 1, 'notes' => 'Merma por defecto #M-005'],
            ['pres' => 'tazas_mag', 'zone' => 'Estante B (Tazas)', 'qty' => 1, 'notes' => 'Merma por rotura #M-006'],
            ['pres' => 'termo_ind', 'zone' => 'Almacén Termos', 'qty' => 5, 'notes' => 'Merma por golpe #M-007'],
            ['pres' => 'polo_pack', 'zone' => 'Bodega Textil Secundaria', 'qty' => 1, 'notes' => 'Merma en paquete #M-008'],
            ['pres' => 'tazas_aa', 'zone' => 'Estante B (Tazas)', 'qty' => 2, 'notes' => 'Merma por acabado #M-009'],
            ['pres' => 'termo_bulk', 'zone' => 'Almacén Termos', 'qty' => 1, 'notes' => 'Merma logística #M-010']
        ];

        foreach ($sales as $s) {
            $this->processOutbound(
                $user,
                $presentations[$s['pres']],
                $zones[$s['zone']],
                $s['qty'],
                $s['notes'],
                'salida'
            );
        }

        foreach ($wastes as $w) {
            $this->processOutbound(
                $user,
                $presentations[$w['pres']],
                $zones[$w['zone']],
                $w['qty'],
                $w['notes'],
                'merma'
            );
        }

        // --- Simulación histórica más completa (últimos 12 meses) ---
        $historicPresentations = array_filter($presentations, function($p){ return $p && $p->id; });
        $months = [];
        for ($i = 11; $i >= 0; $i--) {
            $months[] = \Carbon\Carbon::now()->subMonths($i)->startOfMonth();
        }

        foreach ($historicPresentations as $key => $presentation) {
            // Elegir una zona y supplier predeterminados para esta presentación si existen
            $zone = $zones->first() ?? null;
            $supplierSample = $suppliers->values();

            foreach ($months as $m) {
                // Entrada: cantidad y costo variable
                $qtyIn = rand(10, 80);
                // Calcular unit_cost cercano a una fracción del unit_price (si existe)
                $basePrice = $presentation->unit_price ?? rand(100, 1000);
                $unitCost = round($basePrice * (0.5 + rand(0,30)/100), 2); // entre 50% y 80% del precio
                $totalCost = $unitCost * $qtyIn;

                // Elegir supplier aleatorio
                $supplier = $supplierSample->random();

                // Crear entrada con fecha histórica (día 5 del mes)
                $movementDateIn = $m->copy()->addDays(4)->setTime(10,0,0);
                $this->receiveStock(
                    $user,
                    $presentation,
                    $zone,
                    $supplier,
                    $qtyIn,
                    $totalCost,
                    "Compra histórica {$m->format('Y-m')}",
                    $movementDateIn
                );

                // Venta: generar algunas ventas en la misma mensualidad (día 20)
                $qtySold = rand(0, (int)floor($qtyIn * 0.7));
                if ($qtySold > 0) {
                    $movementDateOut = $m->copy()->addDays(19)->setTime(15,0,0);
                    $this->processOutbound(
                        $user,
                        $presentation,
                        $zone,
                        $qtySold,
                        "Venta histórica {$m->format('Y-m')}",
                        'salida',
                        $movementDateOut
                    );
                }
            }
        }
    }

    /**
     * Función helper para simular InventoryController@storeReceive
     */
    private function receiveStock($user, $presentation, $zone, $supplier, $quantity, $total_cost, $notes, $movementDate = null)
    {
        if (!$presentation || !$zone || !$supplier || !$user) {
            $this->command->error("Faltan datos maestros para simular la recepción.");
            return;
        }

        try {
            DB::transaction(function () use ($user, $presentation, $zone, $supplier, $quantity, $total_cost, $notes, $movementDate) {
                
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
                    'movement_date'   => $movementDate ?? now(),
                ]);
            });
        } catch (\Exception $e) {
            $this->command->error("Error al sembrar inventario: " . $e->getMessage());
        }
    }

    /**
     * Procesa salidas: ventas o merma. Decrementa ubicación y stock y crea InventoryMovement.
     * type: 'salida' | 'merma'
     */
    private function processOutbound($user, $presentation, $zone, $quantity, $notes, $type = 'salida', $movementDate = null)
    {
        if (!$presentation || !$zone || !$user) {
            $this->command->error("Faltan datos maestros para simular la salida ({$type}).");
            return;
        }

        try {
            DB::transaction(function () use ($user, $presentation, $zone, $quantity, $notes, $type, $movementDate) {
                // Obtener la ubicación existente
                $location = ItemLocation::where('presentation_id', $presentation->id)
                    ->where('storage_zone_id', $zone->id)
                    ->first();

                if (!$location) {
                    $this->command->warn("No existe ItemLocation para presentation {$presentation->sku} en zona {$zone->name} — omitiendo salida.");
                    return;
                }

                // Asegurar que no decremente por debajo de 0
                $available = (int) $location->stored_quantity;
                $actualQty = min($available, max(0, (int)$quantity));
                if ($actualQty <= 0) {
                    $this->command->warn("Cantidad disponible insuficiente para {$presentation->sku} en {$zone->name} (solicitado: {$quantity}, disponible: {$available}).");
                    return;
                }

                $m2PerUnit = $presentation->m2_per_unit ?? 0;
                $calculated_occupied_m2 = $actualQty * $m2PerUnit;

                // Decrementar en la ubicación
                $location->decrement('stored_quantity', $actualQty);
                $location->decrement('occupied_m2', $calculated_occupied_m2);

                // Decrementar stock total de la presentación (no menor que 0)
                $currentStock = (int) $presentation->stock_current;
                $newStock = max(0, $currentStock - $actualQty);
                $presentation->update(['stock_current' => $newStock]);

                // Intentar asignar el supplier a partir del último movimiento de entrada
                $lastSupplierId = InventoryMovement::where('presentation_id', $presentation->id)
                                    ->where('type', 'entrada')
                                    ->orderByDesc('movement_date')
                                    ->value('supplier_id');

                // Registrar el movimiento (si no hay supplier previo, quedará null)
                InventoryMovement::create([
                    'presentation_id' => $presentation->id,
                    'user_id' => $user->id,
                    'supplier_id' => $lastSupplierId,
                    'type' => $type,
                    'quantity' => $actualQty,
                    'unit_cost' => $presentation->unit_price ?? 0,
                    'notes' => $notes,
                    'movement_date' => $movementDate ?? now(),
                ]);
            });
        } catch (\Exception $e) {
            $this->command->error("Error al procesar salida ({$type}): " . $e->getMessage());
        }
    }
}