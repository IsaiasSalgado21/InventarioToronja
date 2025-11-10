<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryMovement extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'inventory_movements';

    protected $casts = [
        'movement_date' => 'date',
        'unit_cost' => 'decimal:2'
    ];

    protected $fillable = [
        'presentation_id',
        'user_id',
        'type',
        'quantity',
        'movement_date',
        'notes',
        'supplier_id',
        'unit_cost',
        'created_at',  
        'updated_at'
    ];

    public function presentation()
    {
        return $this->belongsTo(Presentation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
