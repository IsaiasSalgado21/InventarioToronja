<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryMovement extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'inventory_movements';

    protected $fillable = [
        'presentation_id',
        'user_id',
        'type',
        'quantity',
        'movement_date',
        'notes',
    ];

    public function presentation()
    {
        return $this->belongsTo(Presentation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
