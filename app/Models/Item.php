<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'items';

    protected $fillable = [
        'name',
        'description',
        'category_id',
        'supplier_id',
        'abc_class',
        'expiry_date',
    ];
    public function getStockAttribute() {
        return DB::table('presentations')->where('item_id', $this->id)->value('stock') ?? 0;
    }
    
}
