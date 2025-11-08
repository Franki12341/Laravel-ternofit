<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tarea extends Model
{
    protected $fillable = [
        'asignado_por',
        'asignado_a',
        'titulo',
        'descripcion',
        'prioridad',
        'estado',
        'fecha_vencimiento'
    ];

    protected $casts = [
        'fecha_vencimiento' => 'date',
    ];

    public function asignadoPor()
    {
        return $this->belongsTo(User::class, 'asignado_por');
    }

    public function asignadoA()
    {
        return $this->belongsTo(User::class, 'asignado_a');
    }
}