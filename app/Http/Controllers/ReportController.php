<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InventoryMovement;
use App\Models\Presentation;
use App\Models\PriceHistory; // Importar para el nuevo reporte
use App\Models\User; // Importar para el nuevo reporte
use App\Models\Supplier; // Importar para filtros
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf; // Importar para PDF

class ReportController extends Controller
{
    /**
     * Muestra el reporte de comparación de precios de COSTO de proveedores.
     * Analiza la tabla inventory_movements.
     */
    public function priceComparison(Request $request)
    {
        // Validar que si se envía una presentación, esta exista
        $request->validate([
            'presentation_id' => 'nullable|integer|exists:presentations,id'
        ]);

        $selectedPresentationId = $request->input('presentation_id');

        // 1. Obtener la consulta base
        // Queremos movimientos de 'entrada' que tengan un proveedor y un costo
        $query = InventoryMovement::query()
            ->where('type', 'entrada')
            ->whereNotNull('supplier_id')
            ->whereNotNull('unit_cost');

        // 2. Aplicar el filtro si el usuario seleccionó una presentación específica
        if ($selectedPresentationId) {
            $query->where('presentation_id', $selectedPresentationId);
        }

        // 3. Construir la consulta de agregación
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
        
        // 4. Obtener todas las presentaciones para el menú desplegable (el filtro)
        $presentations = Presentation::with('item:id,name')
                            ->select('id', 'item_id', 'sku', 'description')
                            ->orderBy('sku')
                            ->get();

        // 5. Devolver la vista con todos los datos
        return view('reports.price_comparison', compact(
            'comparisonData', 
            'presentations',
            'selectedPresentationId'
        ));
    }

    /**
     * Muestra el reporte de Gasto Total (Cuánto he gastado).
     */
    public function purchaseSummary(Request $request)
    {
        $query = InventoryMovement::where('type', 'entrada');

        // Aplicar filtros de tiempo, proveedor, item...
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }
        if ($request->filled('presentation_id')) {
            $query->where('presentation_id', $request->presentation_id);
        }
        if ($request->filled('start_date')) {
            $query->where('movement_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('movement_date', '<=', $request->end_date . ' 23:59:59');
        }
        
        // Calcular el gasto total
        $totalSpent = $query->clone()->sum(DB::raw('quantity * unit_cost'));
        $totalItemsPurchased = $query->clone()->sum('quantity');

        $purchases = $query->with('presentation.item', 'supplier')
                           ->orderBy('movement_date', 'desc')
                           ->paginate(50)->withQueryString();
        
        // Cargar datos para los filtros
        $suppliers = Supplier::orderBy('name')->get();
        $presentations = Presentation::with('item')->orderBy('sku')->get();

        return view('reports.purchase_summary', compact('purchases', 'totalSpent', 'totalItemsPurchased', 'suppliers', 'presentations', 'request'));
    }

    /**
     * Genera un PDF del reporte de gastos.
     */
    public function downloadPurchaseSummary(Request $request)
    {
        // 1. Re-usar la lógica de filtrado de purchaseSummary
        $query = InventoryMovement::where('type', 'entrada');
        
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }
        if ($request->filled('presentation_id')) {
            $query->where('presentation_id', $request->presentation_id);
        }
        if ($request->filled('start_date')) {
            $query->where('movement_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('movement_date', '<=', $request->end_date . ' 23:59:59');
        }
        
        // 2. Obtener los datos (sin paginar)
        $purchases = $query->with('presentation.item', 'supplier')
                           ->orderBy('movement_date', 'desc')
                           ->get();
        
        // 3. Calcular totales
        $totalSpent = $purchases->sum(fn($p) => $p->quantity * $p->unit_cost);
        $totalItemsPurchased = $purchases->sum('quantity');

        // 4. Preparar datos para la vista PDF
        $data = [
            'purchases' => $purchases,
            'totalSpent' => $totalSpent,
            'totalItemsPurchased' => $totalItemsPurchased,
            'startDate' => $request->start_date,
            'endDate' => $request->end_date,
            'supplier' => $request->filled('supplier_id') ? Supplier::find($request->supplier_id) : null,
            'presentation' => $request->filled('presentation_id') ? Presentation::find($request->presentation_id) : null,
        ];

        // 5. Cargar la vista PDF y generar el PDF
        $pdf = Pdf::loadView('reports.purchase_summary_pdf', $data);
        
        // 6. Devolver el PDF al navegador
        $filename = 'reporte_gastos_' . now()->format('Y-m-d') . '.pdf';
        return $pdf->stream($filename); // Para mostrar en navegador
    }

    /**
     * Muestra el reporte de análisis de márgenes de ganancia.
     */
    public function marginAnalysis(Request $request)
    {
        $presentationsQuery = Presentation::with('item')
            // Carga el PRECIO DE VENTA (de la tabla presentations)
            ->select('id', 'item_id', 'sku', 'description', 'unit_price', 'stock_current') 
            
            // Carga el COSTO PROMEDIO (calculado de inventory_movements)
            ->withAvg(
                // Solo promedia los movimientos de 'entrada' que tienen costo
                ['inventoryMovements' => fn ($query) => $query->where('type', 'entrada')->where('unit_cost', '>', 0)],
                'unit_cost'
            );
            
        $presentations = $presentationsQuery->paginate(20)->withQueryString();

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
            
            // Sumamos a los totales generales (Solo para la página actual)
            $totalValorVenta += $p->valor_total_venta;
            $totalValorCosto += $p->valor_total_costo;
        }

        $totalGananciaPotencial = $totalValorVenta - $totalValorCosto;

        // --- Agregación mensual real (últimos N meses) ---
        $monthsBack = 12; // puedes ajustar a 6 o 24
        $months = [];
        for ($i = $monthsBack - 1; $i >= 0; $i--) {
            $dt = \Carbon\Carbon::now()->subMonths($i);
            $months[] = [
                'label' => $dt->format('M Y'),
                'start' => $dt->copy()->startOfMonth()->toDateString() . ' 00:00:00',
                'end' => $dt->copy()->endOfMonth()->toDateString() . ' 23:59:59',
            ];
        }

        $saleTypes = ['venta', 'salida'];
        $entryTypes = ['entrada'];

        $chartLabels = [];
        $chartRevenue = [];
        $chartCost = [];
        $chartMargin = [];

        foreach ($months as $m) {
            $chartLabels[] = $m['label'];

            // 1) Revenue: sum(quantity * unit_cost) for sales in the month
            $revenue = InventoryMovement::whereIn('type', $saleTypes)
                        ->whereBetween('movement_date', [$m['start'], $m['end']])
                        ->sum(DB::raw('quantity * unit_cost'));

            // 2) For COGS, estimate by taking sales grouped by presentation and multiplying by avg entry cost up to month end
            $salesByPresentation = InventoryMovement::select('presentation_id', DB::raw('SUM(quantity) as qty_sold'))
                                    ->whereIn('type', $saleTypes)
                                    ->whereBetween('movement_date', [$m['start'], $m['end']])
                                    ->groupBy('presentation_id')
                                    ->get();

            $cogs = 0;
            foreach ($salesByPresentation as $row) {
                $pid = $row->presentation_id;
                $qtySold = $row->qty_sold;

                // average entry cost for this presentation up to end of month
                $avgCost = InventoryMovement::where('presentation_id', $pid)
                            ->where('type', 'entrada')
                            ->where('unit_cost', '>', 0)
                            ->where('movement_date', '<=', $m['end'])
                            ->avg('unit_cost') ?? 0;

                $cogs += ($avgCost * $qtySold);
            }

            $margin = $revenue - $cogs;

            $chartRevenue[] = round((float)$revenue, 2);
            $chartCost[] = round((float)$cogs, 2);
            $chartMargin[] = round((float)$margin, 2);
        }

        return view('reports.margin_analysis', compact(
            'presentations', 
            'totalValorVenta', 
            'totalValorCosto', 
            'totalGananciaPotencial',
            'chartLabels',
            'chartRevenue',
            'chartCost',
            'chartMargin',
            'request' // Pasar $request para paginación y filtros
        ));
    }
    
    /**
     * Muestra el reporte de Mermas (Cuánto estoy perdiendo).
     */
    public function lossSummary(Request $request)
    {
        // Define todos los tipos de movimiento que cuentan como pérdida
        $lossTypes = ['merma_recepcion', 'caducado', 'ajuste_salida', 'otro'];
        
        $query = InventoryMovement::whereIn('type', $lossTypes);
        
        // ... (Añadir filtros de tiempo, item, proveedor, etc.) ...
        if ($request->filled('presentation_id')) {
            $query->where('presentation_id', $request->presentation_id);
        }
        if ($request->filled('start_date')) {
            $query->where('movement_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('movement_date', '<=', $request->end_date . ' 23:59:59');
        }
        
        // Agrupar por tipo de merma para ver DÓNDE estás perdiendo
        $lossByReason = $query->clone() // Clonar para no afectar el query principal
                            ->select('type', DB::raw('SUM(quantity) as total_units'), DB::raw('SUM(quantity * unit_cost) as total_cost'))
                            ->groupBy('type')
                            ->get();

        // Agrupar por producto para ver QUÉ estás perdiendo
        $lossByItem = $query->clone()
                           ->select('presentation_id', DB::raw('SUM(quantity) as total_units'), DB::raw('SUM(quantity * unit_cost) as total_cost'))
                           ->groupBy('presentation_id')
                           ->with('presentation.item')
                           ->orderBy('total_cost', 'desc') // Ver lo más costoso primero
                           ->get();
        
        $presentations = Presentation::with('item:id,name')->select('id', 'item_id', 'sku')->orderBy('sku')->get();
        
        return view('reports.loss_summary', compact('lossByReason', 'lossByItem', 'presentations', 'request'));
    }

    /**
     * MUEVO: Muestra el reporte de historial de cambios de PRECIO DE VENTA.
     * Este método analiza la tabla price_histories.
     */
    public function priceHistory(Request $request)
    {
        // 1. Validar filtros
        $request->validate([
            'presentation_id' => 'nullable|integer|exists:presentations,id',
            'user_id' => 'nullable|integer|exists:users,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);
        
        $selectedPresentationId = $request->input('presentation_id');

        // 2. Consulta base
        $query = PriceHistory::with('presentation.item', 'user')
                            ->orderBy('changed_at', 'desc');

        // 3. Aplicar filtros
        if ($selectedPresentationId) {
            $query->where('presentation_id', $selectedPresentationId);
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('start_date')) {
            $query->where('changed_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('changed_at', '<=', $request->end_date . ' 23:59:59');
        }
        
        // 4. Obtener datos para la tabla
        $historyLogs = $query->paginate(20)->withQueryString();
        
        // 5. Preparar datos para la gráfica (SOLO si se filtra por un producto)
        $chartData = null;
        if ($selectedPresentationId) {
            // Volvemos a consultar sin paginar y en orden ascendente para la gráfica
            $chartLogs = $query->clone()->orderBy('changed_at', 'asc')->get();
            
            if ($chartLogs->count() > 1) { // Solo mostrar gráfica si hay al menos 2 puntos de datos
                $chartData = [
                    'labels' => $chartLogs->map(fn($log) => $log->changed_at->format('d/m/Y')),
                    'data'   => $chartLogs->map(fn($log) => $log->new_price),
                ];
            }
        }

        // 6. Obtener datos para los menús desplegables del filtro
        $presentations = Presentation::with('item:id,name')->select('id', 'item_id', 'sku')->orderBy('sku')->get();
        $users = User::select('id', 'name')->orderBy('name')->get();

        // 7. Devolver la vista
        return view('reports.price_history', compact(
            'historyLogs',
            'presentations',
            'users',
            'chartData', // Será null si no se filtra
            'request' // Para repoblar filtros
        ));
    }
}