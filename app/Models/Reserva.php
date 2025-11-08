<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    protected $fillable = [
        'cliente_id',
        'terno_id',
        'fecha_reserva',
        'fecha_evento',
        'fecha_devolucion',
        'monto_total',
        'adelanto',
        'metodo_pago', 
        'saldo',
        'estado',
        'observaciones'
    ];

    protected $casts = [
        'fecha_reserva' => 'date',
        'fecha_evento' => 'date',
        'fecha_devolucion' => 'date',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function terno()
    {
        return $this->belongsTo(Terno::class);
    }
}