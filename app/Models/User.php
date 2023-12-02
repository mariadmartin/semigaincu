<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'apellidos','fecha_nacimiento','sexo', 'direccion_postal','municipio','provincia', 'imagen_perfil','numero_socio', 'fecha_alta', 'fecha_baja', 'es_admin'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'fecha_nacimiento' => 'date:d-m-Y',
        'fecha_alta' => 'date:d-m-Y',
    ];

    /**
     * Obtener la reserva saociada con el usuario.
     */
    public function reserva(): HasOne
    {
        return $this->hasOne(Reserva::class);
    }

    /**
     * Obtener los pagos del usuario.
     */
    public function pagos(): HasMany
    {
        return $this->hasMany(Pago::class);
    }
}