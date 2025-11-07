<?php

namespace App\Http\Controllers;

use App\Models\InventoryMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Presentation;

class InventoryMovementController extends Controller
{
    public function index(Request $request)
    {
        // 1. Validar filtros entrantes
        $request->validate([
            'type' => 'nullable|string|max:50',
            'presentation_id' => 'nullable|integer|exists:presentations,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

    // 2. Definir los tipos de movimiento para los cálculos
    // Incluimos sinónimos que pueden venir de seeders o diferentes procesos
    $lossTypes = ['merma_recepcion', 'merma', 'caducado', 'ajuste_salida', 'otro'];
    $saleTypes = ['venta', 'salida'];
    $entryTypes = ['entrada'];

        // 3. Empezar la consulta de Eloquent
        $query = InventoryMovement::with([
                'presentation' => function ($q) {
                    $q->select('id', 'item_id', 'sku', 'description');
                },
                'presentation.item' => function ($q) {
                    $q->select('id', 'name');
                },
                'user' => function ($q) {
                    $q->select('id', 'name');
                },
                'supplier' => function ($q) {
                    $q->select('id', 'name');
                }
            ])
            ->orderByDesc('movement_date');

        // 4. Aplicar filtros si existen en la URL
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('presentation_id')) {
            $query->where('presentation_id', $request->presentation_id);
        }

        if ($request->filled('start_date')) {
            $query->where('movement_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            // Incluir el día completo
            $query->where('movement_date', '<=', $request->end_date . ' 23:59:59');
        }
        
        // 5. Clonar la consulta ANTES de paginarla, para hacer cálculos totales
        $summaryQuery = clone $query;

        // 6. Obtener los movimientos paginados
        $movements = $query->paginate(50)->withQueryString();

        // 7. Calcular los resúmenes (Totales) usando la consulta clonada
        // Costo total de las mermas (basado en el costo promedio guardado en el movimiento)
        $totalLossCost = (clone $summaryQuery)
                            ->whereIn('type', $lossTypes)
                            ->sum(DB::raw('quantity * unit_cost'));
        
        // Unidades totales perdidas
        $totalLossUnits = (clone $summaryQuery)
                             ->whereIn('type', $lossTypes)
                             ->sum('quantity');

        // Total de unidades vendidas
        $totalSalesUnits = (clone $summaryQuery)
                             ->whereIn('type', $saleTypes)
                             ->sum('quantity');

        // Costo de los bienes vendidos (COGS)
        $totalSalesCost = (clone $summaryQuery)
                             ->whereIn('type', $saleTypes)
                             ->sum(DB::raw('quantity * unit_cost'));

        // 8. Obtener datos para los menús desplegables del filtro
        $presentations = Presentation::with('item:id,name')->select('id', 'item_id', 'sku')->orderBy('sku')->get();
        // Obtener solo los tipos de movimiento que realmente existen en la BD
        $types = InventoryMovement::select('type')->distinct()->pluck('type');

        // 9. Devolver la vista Blade con todos los datos
        return view('movements', compact(
            'movements',
            'presentations',
            'types',
            'totalLossCost',
            'totalLossUnits',
            'totalSalesUnits',
            'totalSalesCost',
            'request'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'presentation_id' => 'required|integer',
            'user_id' => 'required|integer',
            'type' => 'required|string|max:50',
            'quantity' => 'required|integer',
            'notes' => 'nullable|string',
            'movement_date' => 'nullable|date',
        ]);

        $data['movement_date'] = $data['movement_date'] ?? now();

        $movement = InventoryMovement::create($data);

        return response()->json(['message' => 'Movement created', 'id' => $movement->id]);
    }

    public function show($id)
    {
        $movement = InventoryMovement::with([
                'presentation:id,sku,description',
                'user:id,name'
            ])
            ->find($id);

        if (!$movement) {
            return response()->json(['message' => 'Movement not found'], 404);
        }

        return response()->json($movement);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'presentation_id' => 'sometimes|integer',
            'user_id' => 'sometimes|integer',
            'type' => 'sometimes|string|max:50',
            'quantity' => 'sometimes|integer',
            'notes' => 'nullable|string',
            'movement_date' => 'nullable|date',
        ]);

        $movement = InventoryMovement::find($id);

        if (!$movement) {
            return response()->json(['message' => 'Movement not found or deleted'], 404);
        }

        $movement->update($data);

        return response()->json(['message' => 'Movement updated']);
    }

    public function destroy($id)
    {
        $movement = InventoryMovement::find($id);

        if (!$movement) {
            return response()->json(['message' => 'Movement not found or already deleted'], 404);
        }

        $movement->delete();

        return response()->json(['message' => 'Movement deleted (soft)']);
    }
}
