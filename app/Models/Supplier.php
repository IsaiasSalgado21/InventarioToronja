<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'suppliers';

    protected $fillable = ['name', 'contact', 'phone', 'email', 'address', 'RFC'];


    public function inventoryMovements()
    {
        return $this->hasMany(InventoryMovement::class);
    }
    /*
    public function items()
    {
        return $this->hasMany(Item::class);
    }
    */
}
