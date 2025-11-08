<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'rol',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin()
    {
        return $this->rol === 'admin';
    }

    public function actividades()
    {
        return $this->hasMany(Actividad::class);
    }

    public function tareasAsignadas()
    {
        return $this->hasMany(Tarea::class, 'asignado_a');
    }

    public function tareasCreadas()
    {
        return $this->hasMany(Tarea::class, 'asignado_por');
    }
}