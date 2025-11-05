<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InventoryMovement;
use App\Models\Presentation;
use Illuminate\Support\Facades\DB; 

class ReportController extends Controller
{
    /**
     * Muestra el reporte de comparación de precios de proveedores.
     * Este método analiza la tabla inventory_movements.
     */
    public function priceComparison(Request $request)
    {
        // Validar que si se envía una presentación, esta exista
        $request->validate([
            'presentation_id' => 'nullable|integer|exists:presentations,id'
        ]);

        $selectedPresentationId = $request->input('presentation_id');

        // Obtener la consulta base
        // Queremos movimientos de 'entrada' que tengan un proveedor y un costo
        $query = InventoryMovement::query()
            ->where('type', 'entrada')
            ->whereNotNull('supplier_id')
            ->whereNotNull('unit_cost');

        // Aplicar el filtro si el usuario seleccionó una presentación específica
        if ($selectedPresentationId) {
            $query->where('presentation_id', $selectedPresentationId);
        }

        //  Construir la consulta de agregación
        $comparisonData = $query->select(
            'presentation_id',
            'supplier_id',
            DB::raw('AVG(unit_cost) as avg_cost'), // Costo promedio
            DB::raw('MIN(unit_cost) as min_cost'), // Costo más bajo registrado
            DB::raw('MAX(unit_cost) as max_cost'), // Costo más alto registrado
            DB::raw('COUNT(*) as purchase_count'), // Cuántas veces le hemos comprado
            DB::raw('MAX(movement_date) as last_purchase_date') // Cuándo fue la última compra
        )
        ->groupBy('presentation_id', 'supplier_id') // Agrupar por producto Y por proveedor
        ->with([
            'presentation:id,sku,description,item_id', // Cargar relaciones
            'presentation.item:id,name',
            'supplier:id,name'
        ])
        ->orderBy('presentation_id') // Ordenar por producto
        ->orderBy('avg_cost', 'asc') // Ordenar por el más barato primero
        ->get();
        
        // todas las presentaciones para el menú desplegable (el filtro)
        $presentations = Presentation::with('item:id,name')
                            ->select('id', 'item_id', 'sku', 'description')
                            ->orderBy('sku')
                            ->get();

        //  Devolver la vista con todos los datos
        return view('reports.price_comparison', compact(
            'comparisonData', 
            'presentations',
            'selectedPresentationId'
        ));
    }
}