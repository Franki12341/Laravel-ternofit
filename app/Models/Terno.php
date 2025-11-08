<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Terno extends Model
{
    protected $fillable = [
        'codigo',
        'marca',
        'talla',
        'color',
        'categoria',
        'precio_alquiler',
        'estado',
        'descripcion'
    ];

    public function reservas()
    {
        return $this->hasMany(Reserva::class);
    }
}