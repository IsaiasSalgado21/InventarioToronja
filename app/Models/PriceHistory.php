<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PriceHistory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'price_histories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'presentation_id',
        'user_id',
        'old_price',
        'new_price',
        'changed_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'changed_at' => 'datetime', // Es bueno decirle a Eloquent que esto es una fecha
    ];

    // --- RELACIONES FALTANTES ---

    /**
     * RELACIÓN: Un registro de historial PERTENECE A una Presentación.
     */
    public function presentation()
    {
        // Eloquent buscará la llave foránea 'presentation_id'
        return $this->belongsTo(Presentation::class);
    }

    /**
     * RELACIÓN: Un registro de historial PERTENECE A un Usuario (fue hecho por).
     */
    public function user()
    {
        // Eloquent buscará la llave foránea 'user_id'
        return $this->belongsTo(User::class);
    }
}