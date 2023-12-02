<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Reserva extends Model
{
    use HasFactory;

    protected $fillable = [
        'fecha_reserva', 'hora_reserva', 'tiene_luz', 'user_id', 'pista_id'
    ];
    protected $casts = [
        'fecha_reserva' => 'date:d-m-Y'
    ];

    /**
     * Obtener el usuario al que pertenece la reserva. 1-1
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtener la pista que pertenece appends la reserva. N-1
     */
    public function pista(): BelongsTo
    {
        return $this->belongsTo(Pista::class);
    }

    /**
     * Obtener el pago asociado con la pista. N-1
     */
    public function pago(): HasOne
    {
        return $this->hasOne(Pago::class);
    }
}