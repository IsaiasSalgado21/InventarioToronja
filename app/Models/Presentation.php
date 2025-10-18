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
        'units_per_presentation',
        'base_unit',
        'stock_current',
        'stock_minimum',
        'unit_price',
    ];
}
