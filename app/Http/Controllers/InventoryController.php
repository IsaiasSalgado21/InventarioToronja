<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ItemLocation;
use App\Models\Presentation;
use App\Models\StorageZone;
use App\Models\InventoryMovement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class InventoryController extends Controller
{
    
    /**
     * Muestra la vista principal del inventario.
     * Muestra una lista de ItemLocations (stock físico en zonas).
     */
    public function index()
    {
        // --- CORRECCIÓN ---
        // Se eliminó 'presentation.item.supplier' porque Item ya no tiene esa relación.
        // La información del proveedor vive en el movimiento de entrada, no en el stock estático.
        $locations = ItemLocation::with([
            'presentation.item.category', 
            'storageZone'
        ])
            ->orderBy('id', 'desc')
            ->paginate(20);

        return view('inventory.index', compact('locations'));
    }
    
    /**
     * Muestra el formulario para registrar la recepción de stock.
     */
     function showReceiveForm()
    {
        $presentations = Presentation::with('item')->orderBy('sku')->get();
        $storageZones = StorageZone::orderBy('name')->get();
        // Cargar proveedores para el formulario de recepción
        $suppliers = \App\Models\Supplier::orderBy('name')->get();

        return view('inventory.receive', compact('presentations', 'storageZones', 'suppliers'));
    }

    /**
     * Procesa la recepción de stock.
     * Aquí es donde se calcula el 'occupied_m2' opcional.
     */
    public function storeReceive(Request $request)
    {
        // Validación (ahora incluye campos opcionales de costo)
        $data = $request->validate([
            'presentation_id' => 'required|integer|exists:presentations,id',
            'storage_zone_id' => 'required|integer|exists:storage_zones,id',
            'quantity'        => 'required|integer|min:1',
            'quantity_rejected' => 'nullable|integer|min:0', // Para merma en recepción
            'supplier_id'     => 'nullable|integer|exists:suppliers,id', // Para rastreo de costo
            'total_cost'      => 'nullable|numeric|min:0', // Para rastreo de costo
            'notes'           => 'nullable|string|max:255',
        ]);

        try {
            DB::transaction(function () use ($data) {
                
                // --- Lógica de Merma y Costo ---
                $total_received = $data['quantity'];
                $total_rejected = $data['quantity_rejected'] ?? 0;
                $good_quantity = $total_received - $total_rejected; // Unidades que SÍ van al estante

                if ($good_quantity < 0) {
                    throw new \Exception('La cantidad rechazada no puede ser mayor a la cantidad total recibida.');
                }
                
                $presentation = Presentation::find($data['presentation_id']);
                if (!$presentation) {
                    throw new \Exception('Presentación no encontrada.');
                }

                // Calcular costo unitario (basado en el total pagado por el total recibido)
                $unit_cost = 0;
                if (!empty($data['total_cost']) && $total_received > 0) {
                    $unit_cost = $data['total_cost'] / $total_received;
                }
                // --- Fin Lógica Merma y Costo ---

                
                // 1. Añadir solo el STOCK BUENO a la ubicación
                if ($good_quantity > 0) {
                    $location = ItemLocation::firstOrCreate(
                        [
                            'presentation_id' => $data['presentation_id'],
                            'storage_zone_id' => $data['storage_zone_id'],
                        ],
                        [ // Valores por defecto si se crea nuevo
                            'occupied_m2'     => 0, 
                            'stored_quantity' => 0,
                            'assigned_at'     => now(),
                        ]
                    );

                    // Calcular m2 (opcional)
                    $m2PerUnit = $presentation->m2_per_unit ?? 0;
                    $calculated_occupied_m2 = $good_quantity * $m2PerUnit;
                    
                    $location->increment('stored_quantity', $good_quantity);
                    $location->increment('occupied_m2', $calculated_occupied_m2);
                    $presentation->increment('stock_current', $good_quantity);
                }

                // 2. Registrar el movimiento de ENTRADA (por el total comprado)
                InventoryMovement::create([
                    'presentation_id' => $data['presentation_id'],
                    'user_id'         => Auth::id(),
                    'supplier_id'     => $data['supplier_id'] ?? null, // Guardar proveedor
                    'type'            => 'entrada',
                    'quantity'        => $total_received, // Registra el total que se pagó
                    'unit_cost'       => $unit_cost,      // Guardar costo
                    'notes'           => "Recibidas: {$total_received}. Aceptadas: {$good_quantity}. Rechazadas: {$total_rejected}. " . ($data['notes'] ?? ''),
                    'movement_date'   => now(),
                ]);

                // 3. Registrar el movimiento de MERMA (si hubo)
                if ($total_rejected > 0) {
                    InventoryMovement::create([
                        'presentation_id' => $data['presentation_id'],
                        'user_id'         => Auth::id(),
                        'supplier_id'     => $data['supplier_id'] ?? null,
                        'type'            => 'merma_recepcion', // Nuevo tipo
                        'quantity'        => $total_rejected, 
                        'unit_cost'       => $unit_cost, // El costo de lo que se perdió
                        'notes'           => "Rechazado al recibir de proveedor. " . ($data['notes'] ?? ''),
                        'movement_date'   => now(),
                    ]);
                }
                
                // NO actualizamos el precio de venta (unit_price) aquí.
                // $presentation->update(['unit_price' => $unit_cost]); // <-- Esta línea está (correctamente) eliminada.

            });
        } catch (\Exception $e) {
            return back()->with('error', 'Error al recibir stock: ' . $e->getMessage())->withInput();
        }

        return redirect()->route('inventory') // Corregido a 'inventory'
                         ->with('success', 'Stock recibido con éxito.');
    }

    /**
     * Muestra el formulario para transferir stock entre zonas.
     */
    public function showTransferForm()
    {
        // Solo mostrar presentaciones que SÍ tienen stock para mover
        $presentations = Presentation::with('item')->where('stock_current', '>', 0)->orderBy('sku')->get();
        $storageZones = StorageZone::orderBy('name')->get();

        return view('inventory.transfer', compact('presentations', 'storageZones'));
    }

    /**
     * Procesa la transferencia de stock.
     */
    public function storeTransfer(Request $request)
    {
        $data = $request->validate([
            'presentation_id' => 'required|integer|exists:presentations,id',
            'origin_zone_id'  => 'required|integer|exists:storage_zones,id',
            'dest_zone_id'    => 'required|integer|exists:storage_zones,id|different:origin_zone_id',
            'quantity'        => 'required|integer|min:1',
            'notes'           => 'nullable|string|max:255',
        ]);

        try {
            DB::transaction(function () use ($data) {
                
                // 1. Buscar la presentación
                $presentation = Presentation::find($data['presentation_id']);
                if (!$presentation) {
                    throw new \Exception('Presentación no encontrada.');
                }
                
                // 2. Calcular el espacio a mover (será 0 si no está configurado)
                $m2PerUnit = $presentation->m2_per_unit ?? 0;
                $space_to_move = $data['quantity'] * $m2PerUnit;

                // 3. Validar stock en origen
                $locationOrigen = ItemLocation::where('presentation_id', $data['presentation_id'])
                                              ->where('storage_zone_id', $data['origin_zone_id'])
                                              ->first();

                if (!$locationOrigen || $locationOrigen->stored_quantity < $data['quantity']) {
                    throw new \Exception('Stock insuficiente en la zona de origen.');
                }

                // 4. Decrementar cantidad Y espacio en origen
                $locationOrigen->decrement('stored_quantity', $data['quantity']);
                $locationOrigen->decrement('occupied_m2', $space_to_move);

                // 5. Buscar o crear ubicación de destino
                $locationDestino = ItemLocation::firstOrCreate(
                    [
                        'presentation_id' => $data['presentation_id'],
                        'storage_zone_id' => $data['dest_zone_id'],
                    ],
                    [
                        'occupied_m2'     => 0,
                        'stored_quantity' => 0,
                        'assigned_at'     => now(),
                    ]
                );

                // 6. Incrementar cantidad Y espacio en destino
                $locationDestino->increment('stored_quantity', $data['quantity']);
                $locationDestino->increment('occupied_m2', $space_to_move);

                $originZoneName = StorageZone::find($data['origin_zone_id'])->name ?? 'ID '.$data['origin_zone_id'];
                $destZoneName = StorageZone::find($data['dest_zone_id'])->name ?? 'ID '.$data['dest_zone_id'];

                // 7. Registrar el movimiento
                InventoryMovement::create([
                    'presentation_id' => $data['presentation_id'],
                    'user_id'         => Auth::id(),
                    'type'            => 'transferencia',
                    'quantity'        => $data['quantity'],
                    // El costo (unit_cost) es null aquí, porque no es una compra
                    'notes'           => "De: {$originZoneName} -> A: {$destZoneName}. " . ($data['notes'] ?? ''),
                    'movement_date'   => now(),
                ]);

            });
        } catch (\Exception $e) {
            return back()->with('error', 'Error al transferir stock: ' . $e->getMessage())->withInput();
        }

        return redirect()->route('inventory') // Corregido a 'inventory'
                         ->with('success', 'Transferencia realizada con éxito.');
    }

    /**
     * Muestra el formulario para registrar la salida de stock.
     */
    public function showRemoveForm()
    {
        // Solo mostrar presentaciones que SÍ tienen stock
        $presentations = Presentation::with('item')->where('stock_current', '>', 0)->orderBy('sku')->get();
        // Cargar zonas (para el <select> de origen)
        $storageZones = StorageZone::orderBy('name')->get();

        return view('inventory.remove', compact('presentations', 'storageZones'));
    }

    /**
     * Procesa la salida de stock (venta, merma, ajuste).
     */
    public function storeRemove(Request $request)
    {
        $data = $request->validate([
            'presentation_id' => 'required|integer|exists:presentations,id',
            'storage_zone_id' => 'required|integer|exists:storage_zones,id',
            'quantity'        => 'required|integer|min:1',
            'type'            => 'required|string|in:venta,caducado,ajuste_salida,otro', // Valida los tipos
            'notes'           => 'nullable|string|max:255',
        ]);

        try {
            DB::transaction(function () use ($data) {
                
                // 1. Buscar la presentación
                $presentation = Presentation::find($data['presentation_id']);
                if (!$presentation) {
                    throw new \Exception('Presentación no encontrada.');
                }
                
                // 2. Validar stock en origen
                $locationOrigen = ItemLocation::where('presentation_id', $data['presentation_id'])
                                              ->where('storage_zone_id', $data['storage_zone_id'])
                                              ->first();

                if (!$locationOrigen || $locationOrigen->stored_quantity < $data['quantity']) {
                    throw new \Exception('Stock insuficiente en la zona de origen para esta salida.');
                }
                
                // 3. Calcular espacio proporcional a restar
                $m2PerUnit = $presentation->m2_per_unit ?? 0;
                $space_to_remove = $data['quantity'] * $m2PerUnit;

                // 4. Decrementar stock y espacio en la ubicación
                $locationOrigen->decrement('stored_quantity', $data['quantity']);
                $locationOrigen->decrement('occupied_m2', $space_to_remove);

                // 5. Decrementar el stock total de la presentación
                $presentation->decrement('stock_current', $data['quantity']);
                
                // 6. Obtener el costo promedio para registrar la pérdida/salida
                $avg_cost = $presentation->inventoryMovements()
                                   ->where('type', 'entrada')
                                   ->where('unit_cost', '>', 0)
                                   ->avg('unit_cost') ?? 0;

                // 7. Registrar el movimiento de salida
                InventoryMovement::create([
                    'presentation_id' => $data['presentation_id'],
                    'user_id'         => Auth::id(),
                    'type'            => $data['type'], // 'venta', 'caducado', etc.
                    'quantity'        => $data['quantity'], // O -$data['quantity']
                    'unit_cost'       => $avg_cost, // Registra el costo de lo que se saca
                    'notes'           => $data['notes'] ?? '',
                    'movement_date'   => now(),
                ]);

            });
        } catch (\Exception $e) {
            return back()->with('error', 'Error al registrar la salida: ' . $e->getMessage())->withInput();
        }

        return redirect()->route('inventory') // Corregido a 'inventory'
                         ->with('success', 'Salida de stock registrada con éxito.');
    }
}