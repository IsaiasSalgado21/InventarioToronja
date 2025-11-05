<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\InventoryMovement;
use App\Models\Item;
use App\Models\Presentation;
use App\Models\Supplier;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
    
        $totalItems = Item::count();
        $totalCategories = Category::count();
        $totalSuppliers = Supplier::count();
        $totalMovements = InventoryMovement::count();

        $items = Item::with('category', 'presentations')
                        ->latest('id') 
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
        $users = User::all();
        $categories = Category::all();
        $suppliers = Supplier::all();
        $presentations = Presentation::with('item.category')->get();

        return view('management', compact('users', 'categories', 'suppliers', 'presentations'));
    }
}
