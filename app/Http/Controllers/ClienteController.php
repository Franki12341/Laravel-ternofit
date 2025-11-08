<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Actividad;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $query = Cliente::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nombre', 'LIKE', "%{$search}%")
                  ->orWhere('apellido', 'LIKE', "%{$search}%")
                  ->orWhere('dni', 'LIKE', "%{$search}%");
        }

        $clientes = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        return view('clientes.create');
    }

    public function store(Request $request)
    {
        // ========== VALIDACIONES MEJORADAS ==========
        $request->validate([
            'dni' => [
                'required',
                'digits:8',              // Exactamente 8 dígitos
                'numeric',               // Solo números
                'unique:clientes,dni'    // Único en la tabla
            ],
            'nombre' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'  // Solo letras, espacios y tildes
            ],
            'apellido' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'
            ],
            'telefono' => [
                'required',
                'digits:9',              // Exactamente 9 dígitos
                'numeric',
                'regex:/^9[0-9]{8}$/'   // Debe empezar con 9
            ],
            'email' => [
                'nullable',
                'email:rfc,dns',         // Validación estricta de email
                'max:255'
            ],
            'direccion' => [
                'nullable',
                'string',
                'max:500'
            ],
        ], [
            // Mensajes personalizados en español
            'dni.required' => 'El DNI es obligatorio',
            'dni.digits' => 'El DNI debe tener exactamente 8 dígitos',
            'dni.numeric' => 'El DNI solo puede contener números',
            'dni.unique' => 'Este DNI ya está registrado en el sistema',
            
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.regex' => 'El nombre solo puede contener letras y espacios',
            
            'apellido.required' => 'El apellido es obligatorio',
            'apellido.regex' => 'El apellido solo puede contener letras y espacios',
            
            'telefono.required' => 'El teléfono es obligatorio',
            'telefono.digits' => 'El teléfono debe tener exactamente 9 dígitos',
            'telefono.regex' => 'El teléfono debe ser un número de celular válido (empezar con 9)',
            
            'email.email' => 'El email debe ser una dirección válida',
        ]);

        $cliente = Cliente::create($request->all());

        // Registrar actividad
        Actividad::create([
            'user_id' => auth()->id(),
            'tipo' => 'Cliente Creado',
            'descripcion' => "Registró nuevo cliente: {$cliente->nombre} {$cliente->apellido}",
            'valor' => null
        ]);

        return redirect()->route('clientes.index')->with('success', 'Cliente creado exitosamente');
    }

    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        // ========== VALIDACIONES MEJORADAS (permite mismo DNI del cliente actual) ==========
        $request->validate([
            'dni' => [
                'required',
                'digits:8',
                'numeric',
                'unique:clientes,dni,' . $cliente->id  // Ignora el DNI del cliente actual
            ],
            'nombre' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'
            ],
            'apellido' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'
            ],
            'telefono' => [
                'required',
                'digits:9',
                'numeric',
                'regex:/^9[0-9]{8}$/'
            ],
            'email' => [
                'nullable',
                'email:rfc,dns',
                'max:255'
            ],
            'direccion' => [
                'nullable',
                'string',
                'max:500'
            ],
        ], [
            'dni.required' => 'El DNI es obligatorio',
            'dni.digits' => 'El DNI debe tener exactamente 8 dígitos',
            'dni.numeric' => 'El DNI solo puede contener números',
            'dni.unique' => 'Este DNI ya está registrado por otro cliente',
            
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.regex' => 'El nombre solo puede contener letras y espacios',
            
            'apellido.required' => 'El apellido es obligatorio',
            'apellido.regex' => 'El apellido solo puede contener letras y espacios',
            
            'telefono.required' => 'El teléfono es obligatorio',
            'telefono.digits' => 'El teléfono debe tener exactamente 9 dígitos',
            'telefono.regex' => 'El teléfono debe ser un número de celular válido (empezar con 9)',
            
            'email.email' => 'El email debe ser una dirección válida',
        ]);

        $cliente->update($request->all());

        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado exitosamente');
    }

    public function destroy(Cliente $cliente)
    {
        // Verificar si tiene reservas asociadas
        if ($cliente->reservas()->count() > 0) {
            return redirect()->route('clientes.index')->with('error', 'No se puede eliminar el cliente porque tiene reservas asociadas');
        }

        $cliente->delete();
        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado exitosamente');
    }

    public function show(Cliente $cliente)
    {
        $reservas = $cliente->reservas()->with('terno')->orderBy('created_at', 'desc')->get();
        return view('clientes.show', compact('cliente', 'reservas'));
    }
}