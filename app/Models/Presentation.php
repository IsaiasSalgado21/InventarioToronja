<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Presentation extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'presentations';

    protected $fillable = [
        'item_id',
        'sku',
        'description',
        'archetype',
        'quality',
        'units_per_presentation',
        'base_unit',
        'stock_current',
        'stock_minimum',
        'unit_price',
        'm2_per_unit',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function itemLocations()
    {
        return $this->hasMany(ItemLocation::class);
    }

    public function inventoryMovements()
    {
        return $this->hasMany(InventoryMovement::class);
    }

    public function priceHistories()
    {
        return $this->hasMany(PriceHistory::class);
    }

    public function storageZones()
    {
        return $this->belongsToMany(StorageZone::class, 'item_locations')
                    ->withPivot('stored_quantity', 'occupied_m2', 'id')
                    ->withTimestamps();
    }
}
