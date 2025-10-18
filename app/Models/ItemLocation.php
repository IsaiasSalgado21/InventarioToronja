<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class ItemLocation extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'item_locations';

    protected $fillable = [
        'presentation_id',
        'storage_zone_id',
        'occupied_m2',
        'stored_quantity',
        'assigned_at',
    ];
}
