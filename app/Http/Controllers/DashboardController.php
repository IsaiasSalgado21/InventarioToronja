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
        $totalStock = DB::table('presentations')->sum('stock_current');

        $items = DB::table('items as i')
            ->leftJoin('categories as c', 'i.category_id', '=', 'c.id')
            ->leftJoin('suppliers as s', 'i.supplier_id', '=', 's.id')
            ->leftJoin('presentations as p', 'p.item_id', '=', 'i.id')
            ->leftJoin('item_locations as l', 'p.id', '=', 'l.presentation_id')
            ->leftJoin('storage_zones as z', 'l.storage_zone_id', '=', 'z.id')
            ->select(
                'i.id',
                'i.name',
                'c.name as category_name',
                's.name as supplier_name',
                'p.sku as presentation_sku',
                'p.description as presentation_description',
                'p.stock_current as stock',
                'z.name as location_name',
                DB::raw('COALESCE(p.stock_current, 0) as stock')
            )
            ->orderBy('i.id', 'desc')
            ->get();

        return view('dashboard', compact(
            'totalItems',
            'totalCategories',
            'totalSuppliers',
            'totalMovements',
            'items'
        ));
    }
    public function management()
    {
        $users = DB::table('users')->get();
        $categories = DB::table('categories')->get();
        $suppliers = DB::table('suppliers')->get();
        $presentations = DB::table('presentations as p')
            ->join('items as i', 'p.item_id', '=', 'i.id')
            ->join('categories as c', 'i.category_id', '=', 'c.id')
            ->select('p.*', 'i.name as item_name', 'c.name as category_name')
            ->get();

        return view('management', compact('users', 'categories', 'suppliers', 'presentations'));
    }
}
