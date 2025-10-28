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
   
    public function index()
    {
        $locations = ItemLocation::with('presentation.item.supplier', 'presentation.item.category', 'storageZone')
                                   ->orderBy('id', 'desc')
                                   ->paginate(20);

        return view('inventory.index', compact('locations'));
    }
     function showReceiveForm()
    {
        $presentations = Presentation::with('item')->orderBy('sku')->get();
        $storageZones = StorageZone::orderBy('name')->get();

        return view('inventory.receive', compact('presentations', 'storageZones'));
    }

    public function storeReceive(Request $request)
    {
        $data = $request->validate([
            'presentation_id' => 'required|integer|exists:presentations,id',
            'storage_zone_id' => 'required|integer|exists:storage_zones,id',
            'quantity'        => 'required|integer|min:1',
            'notes'           => 'nullable|string|max:255',
        ]);

        try {
            DB::transaction(function () use ($data) {
                $location = ItemLocation::firstOrCreate(
                    [
                        'presentation_id' => $data['presentation_id'],
                        'storage_zone_id' => $data['storage_zone_id'],
                    ],
                    [
                        'occupied_m2'     => 0, 
                        'stored_quantity' => 0,
                        'assigned_at'     => now(),
                    ]
                );

                $location->increment('stored_quantity', $data['quantity']);

                $presentation = Presentation::find($data['presentation_id']);
                $presentation->increment('stock_current', $data['quantity']);

                InventoryMovement::create([
                    'presentation_id' => $data['presentation_id'],
                    'user_id'         => Auth::id(),
                    'type'            => 'entrada',
                    'quantity'        => $data['quantity'],
                    'notes'           => $data['notes'],
                    'movement_date'   => now(),
                ]);
            });
        } catch (\Exception $e) {
            return back()->with('error', 'Error al recibir stock: ' . $e->getMessage())->withInput();
        }

        return redirect()->route('inventory.index')
                         ->with('success', 'Stock recibido con éxito.');
    }

    public function showTransferForm()
    {
        $presentations = Presentation::with('item')->where('stock_current', '>', 0)->orderBy('sku')->get();
        $storageZones = StorageZone::orderBy('name')->get();

        return view('inventory.transfer', compact('presentations', 'storageZones'));
    }

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
                $locationOrigen = ItemLocation::where('presentation_id', $data['presentation_id'])
                                              ->where('storage_zone_id', $data['origin_zone_id'])
                                              ->first();

                if (!$locationOrigen || $locationOrigen->stored_quantity < $data['quantity']) {
                    throw new \Exception('Stock insuficiente en la zona de origen.');
                }

                $locationOrigen->decrement('stored_quantity', $data['quantity']);

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
                $locationDestino->increment('stored_quantity', $data['quantity']);

                $originZoneName = StorageZone::find($data['origin_zone_id'])->name ?? 'ID '.$data['origin_zone_id'];
                $destZoneName = StorageZone::find($data['dest_zone_id'])->name ?? 'ID '.$data['dest_zone_id'];

                InventoryMovement::create([
                    'presentation_id' => $data['presentation_id'],
                    'user_id'         => Auth::id(),
                    'type'            => 'transferencia',
                    'quantity'        => $data['quantity'],
                    'notes'           => "De: {$originZoneName} -> A: {$destZoneName}. " . ($data['notes'] ?? ''),
                    'movement_date'   => now(),
                ]);

            });
        } catch (\Exception $e) {
            return back()->with('error', 'Error al transferir stock: ' . $e->getMessage())->withInput();
        }

        return redirect()->route('inventory.index')
                         ->with('success', 'Transferencia realizada con éxito.');
    }
}