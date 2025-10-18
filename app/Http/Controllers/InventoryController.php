<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function index()
    {
        $inventory = DB::table('presentations as p')
            ->join('items as i', 'p.item_id', '=', 'i.id')
            ->leftJoin('categories as c', 'i.category_id', '=', 'c.id')
            ->leftJoin('suppliers as s', 'i.supplier_id', '=', 's.id')
            ->leftJoin('item_locations as il', 'p.id', '=', 'il.presentation_id')
            ->leftJoin('storage_zones as sz', 'il.storage_zone_id', '=', 'sz.id')
            ->whereNull('p.deleted_at')
            ->whereNull('i.deleted_at')
            ->whereNull('c.deleted_at')
            ->whereNull('s.deleted_at')
            ->whereNull('sz.deleted_at')
            ->select(
                'p.id',
                'p.sku',
                'p.description as presentation_desc',
                'p.stock_current',
                'p.stock_minimum',
                'p.unit_price',
                'i.name as item_name',
                'c.name as category_name',
                's.name as supplier_name',
                'sz.name as storage_zone',
                'il.stored_quantity',
                'il.occupied_m2'
            )
            ->get();

        return view('inventory', compact('inventory'));
    }
}
