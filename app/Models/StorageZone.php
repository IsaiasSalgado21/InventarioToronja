<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StorageZone extends Model
{
    use HasFactory;

    protected $table = 'storage_zones';

    protected $fillable = [
        'name',
        'description',
        'dimension_x',
        'dimension_y',
        'capacity_m2',
    ];
}
