<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presentation extends Model
{
    use HasFactory;

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
