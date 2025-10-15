<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
    
        $totalItems = DB::table('items')->count();
        $totalCategories = DB::table('categories')->count();
        $totalSuppliers = DB::table('suppliers')->count();
        $totalMovements = DB::table('inventory_movements')->count();

        $items = DB::table('items as i')
            ->leftJoin('categories as c', 'i.category_id', '=', 'c.id')
            ->leftJoin('suppliers as s', 'i.supplier_id', '=', 's.id')
            ->leftJoin('presentations as p', 'p.item_id', '=', 'i.id')
            ->select(
                'i.id',
                'i.name',
                'c.name as category_name',
                's.name as supplier_name',
                DB::raw('COALESCE(p.stock_current, 0) as stock')
            )
            ->orderBy('i.id', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard', compact(
            'totalItems',
            'totalCategories',
            'totalSuppliers',
            'totalMovements',
            'items'
        ));
    }
}
