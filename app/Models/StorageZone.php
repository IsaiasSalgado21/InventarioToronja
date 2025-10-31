<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StorageZone extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'storage_zones';

    protected $fillable = [
        'name',
        'description',
        'dimension_x',
        'dimension_y',
        'capacity_m2',
        'capacity_units',
    ];

    protected $casts = [
        'capacity_m2' => 'float',
        'capacity_units' => 'integer',
    ];
    
    public function itemLocations()
    {
        return $this->hasMany(ItemLocation::class);
    }

    public function presentations()
    {
        return $this->belongsToMany(Presentation::class, 'item_locations')
                    ->withPivot('stored_quantity', 'occupied_m2', 'id')
                    ->withTimestamps();
    }
}
