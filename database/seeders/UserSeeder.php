<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Usuario Administrador
        User::create([
            'name' => 'Admin TernoFit',
            'email' => 'admin@ternofit.com',
            'password' => Hash::make('admin123'),
            'rol' => 'admin'
        ]);

        // Usuario Empleado
        User::create([
            'name' => 'Empleado TernoFit',
            'email' => 'empleado@ternofit.com',
            'password' => Hash::make('empleado123'),
            'rol' => 'empleado'
        ]);
    }
}