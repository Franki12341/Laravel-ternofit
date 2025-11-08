<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Actividad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $usuarios = User::orderBy('created_at', 'desc')->paginate(10);
        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        return view('usuarios.create');
    }

    public function store(Request $request)
    {
        // ========== VALIDACIONES MEJORADAS ==========
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'  // Solo letras y espacios
            ],
            'email' => [
                'required',
                'email:rfc,dns',
                'max:255',
                'unique:users,email'
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',  // Debe coincidir con password_confirmation
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'  // Al menos 1 mayúscula, 1 minúscula, 1 número
            ],
            'role' => [
                'required',
                'in:Administrador,Empleado'
            ],
        ], [
            // Mensajes personalizados
            'name.required' => 'El nombre es obligatorio',
            'name.regex' => 'El nombre solo puede contener letras y espacios',
            
            'email.required' => 'El email es obligatorio',
            'email.email' => 'El email debe ser una dirección válida',
            'email.unique' => 'Este email ya está registrado en el sistema',
            
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'password.regex' => 'La contraseña debe contener al menos: 1 mayúscula, 1 minúscula y 1 número',
            
            'role.required' => 'El rol es obligatorio',
            'role.in' => 'El rol debe ser Administrador o Empleado',
        ]);

        $usuario = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // Registrar actividad
        Actividad::create([
            'user_id' => auth()->id(),
            'tipo' => 'Usuario Creado',
            'descripcion' => "Registró nuevo usuario: {$usuario->name} ({$usuario->role})",
            'valor' => null
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado exitosamente');
    }

    public function edit(User $usuario)
    {
        return view('usuarios.edit', compact('usuario'));
    }

    public function update(Request $request, User $usuario)
    {
        // ========== VALIDACIONES MEJORADAS ==========
        $rules = [
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'
            ],
            'email' => [
                'required',
                'email:rfc,dns',
                'max:255',
                'unique:users,email,' . $usuario->id  // Permite el email actual del usuario
            ],
            'role' => [
                'required',
                'in:Administrador,Empleado'
            ],
        ];

        // Si se proporciona contraseña, validarla
        if ($request->filled('password')) {
            $rules['password'] = [
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'
            ];
        }

        $messages = [
            'name.required' => 'El nombre es obligatorio',
            'name.regex' => 'El nombre solo puede contener letras y espacios',
            
            'email.required' => 'El email es obligatorio',
            'email.email' => 'El email debe ser una dirección válida',
            'email.unique' => 'Este email ya está siendo usado por otro usuario',
            
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'password.regex' => 'La contraseña debe contener al menos: 1 mayúscula, 1 minúscula y 1 número',
            
            'role.required' => 'El rol es obligatorio',
            'role.in' => 'El rol debe ser Administrador o Empleado',
        ];

        $request->validate($rules, $messages);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        // Solo actualizar contraseña si se proporciona
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $usuario->update($data);

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado exitosamente');
    }

    public function destroy(User $usuario)
    {
        // No permitir eliminar el propio usuario
        if ($usuario->id === auth()->id()) {
            return redirect()->route('usuarios.index')->with('error', 'No puedes eliminar tu propio usuario');
        }

        // Verificar si tiene actividades asociadas
        if ($usuario->actividades()->count() > 0) {
            return redirect()->route('usuarios.index')->with('error', 'No se puede eliminar el usuario porque tiene actividades registradas en el sistema');
        }

        $usuario->delete();
        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado exitosamente');
    }
}