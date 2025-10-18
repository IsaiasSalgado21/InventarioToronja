<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PriceHistory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'price_histories';

    protected $fillable = [
        'presentation_id',
        'old_price',
        'new_price',
        'changed_at',
    ];
}
