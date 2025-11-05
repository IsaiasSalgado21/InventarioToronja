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
    public function marginAnalysis(Request $request)
    {
        $presentations = Presentation::with('item')
            // Carga el PRECIO DE VENTA (de la tabla presentations)
            ->select('id', 'item_id', 'sku', 'description', 'unit_price', 'stock_current') 

            // Carga el COSTO PROMEDIO (calculado de inventory_movements)
            ->withAvg(
                // Solo promedia los movimientos de 'entrada' que tienen costo
                ['inventoryMovements' => fn ($query) => $query->where('type', 'entrada')->where('unit_cost', '>', 0)],
                'unit_cost'
            )

            // Carga el COSTO TOTAL (Costo Promedio * Stock Actual)
            ->withSum(
                ['inventoryMovements' => fn ($query) => $query->where('type', 'entrada')->where('unit_cost', '>', 0)],
                'unit_cost' // Esto es un truco, lo ajustaremos abajo
            )
            ->paginate(20);

        // Calculamos los totales en el backend
        $totalValorVenta = 0;
        $totalValorCosto = 0;

        foreach ($presentations as $p) {
            // El costo promedio ya viene cargado por withAvg
            $costoPromedio = $p->inventory_movements_avg_unit_cost ?? 0;

            // Calculamos la ganancia por unidad
            $p->ganancia_por_unidad = $p->unit_price - $costoPromedio;

            // Calculamos el margen en %
            $p->margen_porcentaje = ($p->unit_price > 0) 
                                    ? ($p->ganancia_por_unidad / $p->unit_price) * 100
                                    : 0;

            // Calculamos el valor total de este stock
            $p->valor_total_venta = $p->unit_price * $p->stock_current;
            $p->valor_total_costo = $costoPromedio * $p->stock_current;

            // Sumamos a los totales generales
            $totalValorVenta += $p->valor_total_venta;
            $totalValorCosto += $p->valor_total_costo;
        }

        $totalGananciaPotencial = $totalValorVenta - $totalValorCosto;

        return view('reports.margin_analysis', compact(
            'presentations', 
            'totalValorVenta', 
            'totalValorCosto', 
            'totalGananciaPotencial'
        ));
    }
}