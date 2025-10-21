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
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    public function presentations()
    {
        return $this->hasMany(Presentation::class);
    }
    public function getStockTotalAttribute()
    {
        if ($this->relationLoaded('presentations')) {
            return $this->presentations->sum('stock_current');
        }
        
        return $this->presentations()->sum('stock_current');
    }

    
}
