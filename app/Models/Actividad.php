<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Actividad extends Model
{
    protected $table = 'actividades';
    
    protected $fillable = [
        'user_id',
        'tipo',
        'descripcion',
        'valor'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}