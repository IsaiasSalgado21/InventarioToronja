<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\InventoryMovement;
use App\Models\Item;
use App\Models\Presentation;
use App\Models\Supplier;
use App\Models\User; 
use Illuminate\Support\Facades\DB;
use Illuminate\support\Facades\Gate;

class DashboardController extends Controller
{
    public function index()
    {
        if (Gate::denies('is-admin')) {
            return redirect()->route('inventory');
        }

        // KPI 1: Alertas de Stock Bajo
        $totalStockAlerts = Presentation::where('stock_minimum', '>', 0)
            ->whereRaw('stock_current <= stock_minimum')
            ->count();

        // Obtenemos todas las presentaciones que tienen stock para los cálculos
        $presentationsInStock = Presentation::with('item.category')
            ->where('stock_current', '>', 0)
            ->withAvg(
                ['inventoryMovements' => fn ($q) => $q->where('type', 'entrada')->where('unit_cost', '>', 0)],
                'unit_cost'
            )
            ->get();

        $totalValorCosto = 0;
        $totalValorVenta = 0;
        $valorPorCategoria = []; // Para la Gráfica 1

        foreach ($presentationsInStock as $p) {
            $costoPromedio = $p->inventory_movements_avg_unit_cost ?? 0;
            $valorStockCosto = $p->stock_current * $costoPromedio;
            $valorStockVenta = $p->stock_current * $p->unit_price;

            // Sumar para las tarjetas KPI
            $totalValorCosto += $valorStockCosto;
            $totalValorVenta += $valorStockVenta;
            
            // Agrupar para la Gráfica 1 (Torta)
            $categoryName = $p->item?->category?->name ?? 'Sin Categoría';
            if (!isset($valorPorCategoria[$categoryName])) {
                $valorPorCategoria[$categoryName] = 0;
            }
            $valorPorCategoria[$categoryName] += $valorStockCosto;
        }

        // KPI 2: Valor Total del Inventario (a Costo)
        // ($totalValorCosto ya se calculó arriba)

        // KPI 3: Ganancia Potencial Total
        $totalGananciaPotencial = $totalValorVenta - $totalValorCosto;

        // KPI 4: Merma Total (Pérdidas) del Mes Actual
        $lossTypes = ['merma_recepcion', 'caducado', 'ajuste_salida', 'otro'];
        $totalMermaMes = InventoryMovement::whereIn('type', $lossTypes)
            ->whereYear('movement_date', now()->year)
            ->whereMonth('movement_date', now()->month)
            ->sum(DB::raw('quantity * unit_cost')); // Suma el valor de costo de la merma


        // ----- 2. LÓGICA PARA GRÁFICAS -----

        // Gráfica 1: Valor por Categoría (Torta)
        $chartValorLabels = array_keys($valorPorCategoria);
        $chartValorData = array_values($valorPorCategoria);


        // Gráfica 2: Productos con Stock Bajo (Barras)
        $stockBajo = Presentation::with('item')
            ->where('stock_minimum', '>', 0)
            ->whereRaw('stock_current <= stock_minimum')
            ->orderByRaw('(stock_current / stock_minimum) ASC')
            ->limit(5)
            ->get();
        
        $chartStockBajoLabels = $stockBajo->map(fn($p) => $p->sku);
        $chartStockBajoData_Actual = $stockBajo->map(fn($p) => $p->stock_current);
        $chartStockBajoData_Minimo = $stockBajo->map(fn($p) => $p->stock_minimum);


        // Gráfica 3: Flujo de Mercancía (Líneas)
        $meses = [];
        $labels = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $meses[$date->format('Y-m')] = 0;
            $labels[] = $date->format('M Y');
        }

        $saleTypes = ['venta'];
        $lossTypes = ['merma_recepcion', 'caducado', 'ajuste_salida', 'otro'];

        $gastos = InventoryMovement::where('type', 'entrada')
            ->where('movement_date', '>=', now()->subMonths(5)->startOfMonth())
            ->select(DB::raw('DATE_FORMAT(movement_date, "%Y-%m") as month_year'), DB::raw('SUM(quantity * unit_cost) as total'))
            ->groupBy('month_year')
            ->get()
            ->pluck('total', 'month_year');
            
        $ventas = InventoryMovement::whereIn('type', $saleTypes)
            ->where('movement_date', '>=', now()->subMonths(5)->startOfMonth())
            ->select(DB::raw('DATE_FORMAT(movement_date, "%Y-%m") as month_year'), DB::raw('SUM(quantity * unit_cost) as total'))
            ->groupBy('month_year')
            ->get()
            ->pluck('total', 'month_year');

        $perdidas = InventoryMovement::whereIn('type', $lossTypes)
            ->where('movement_date', '>=', now()->subMonths(5)->startOfMonth())
            ->select(DB::raw('DATE_FORMAT(movement_date, "%Y-%m") as month_year'), DB::raw('SUM(quantity * unit_cost) as total'))
            ->groupBy('month_year')
            ->get()
            ->pluck('total', 'month_year');
        
        $chartFlujoLabels = $labels;
        $chartFlujoData_Gastos = array_values(array_merge($meses, $gastos->all())); 
        $chartFlujoData_Ventas = array_values(array_merge($meses, $ventas->all())); 
        $chartFlujoData_Perdidas = array_values(array_merge($meses, $perdidas->all()));


        // ----- 3. ENVIAR DATOS A LA VISTA -----
        
        return view('dashboard', compact(
            'totalStockAlerts',
            'totalValorCosto',
            'totalGananciaPotencial',
            'totalMermaMes',
            'chartValorLabels',
            'chartValorData',
            'chartStockBajoLabels',
            'chartStockBajoData_Actual',
            'chartStockBajoData_Minimo',
            'chartFlujoLabels',
            'chartFlujoData_Gastos',
            'chartFlujoData_Ventas',  
            'chartFlujoData_Perdidas'
        ));
    }
    
    public function management()
    {
        // Este es el método 'management' que refactorizamos a Eloquent antes
        $users = User::all();
        $categories = Category::all();
        $suppliers = Supplier::all();
        
        $presentations = Presentation::with('item.category')->get();

        return view('management', compact('users', 'categories', 'suppliers', 'presentations'));
    }
}