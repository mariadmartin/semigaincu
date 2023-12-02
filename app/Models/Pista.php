<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pista extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipo_pista', 'num_pista'
    ];

    /**
     * Obtener las reservas para la pista. 1-n
     */
    public function reservas(): HasMany
    {
        return $this->hasMany(Reserva::class);
    }
}
