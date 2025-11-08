<?php

namespace App\Http\Controllers;

use App\Models\Terno;
use App\Models\Actividad;
use Illuminate\Http\Request;

class TernoController extends Controller
{
    public function index(Request $request)
    {
        $query = Terno::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('codigo', 'LIKE', "%{$search}%")
                  ->orWhere('marca', 'LIKE', "%{$search}%")
                  ->orWhere('categoria', 'LIKE', "%{$search}%");
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }

        $ternos = $query->orderBy('created_at', 'desc')->paginate(12);
        return view('ternos.index', compact('ternos'));
    }

    public function create()
    {
        return view('ternos.create');
    }

    public function store(Request $request)
    {
        // ========== VALIDACIONES MEJORADAS ==========
        $request->validate([
            'codigo' => [
                'required',
                'string',
                'max:50',
                'unique:ternos,codigo',
                'regex:/^[A-Z0-9\-]+$/'  // Solo mayúsculas, números y guiones
            ],
            'marca' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s\-\.]+$/'  // Letras, números, espacios, guiones y puntos
            ],
            'categoria' => [
                'required',
                'in:Premium,Clásico,Moderno,Infantil'  // Solo valores permitidos
            ],
            'talla' => [
                'required',
                'in:S,M,L,XL,XXL'  // Solo tallas válidas
            ],
            'color' => [
                'required',
                'string',
                'max:100',
                'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'  // Solo letras y espacios
            ],
            'precio_alquiler' => [
                'required',
                'numeric',
                'min:0',
                'max:9999.99',
                'regex:/^\d+(\.\d{1,2})?$/'  // Máximo 2 decimales
            ],
            'estado' => [
                'required',
                'in:Disponible,Alquilado,Mantenimiento'
            ],
            'descripcion' => [
                'nullable',
                'string',
                'max:1000'
            ],
        ], [
            // Mensajes personalizados en español
            'codigo.required' => 'El código es obligatorio',
            'codigo.unique' => 'Este código ya existe en el sistema',
            'codigo.regex' => 'El código solo puede contener letras mayúsculas, números y guiones (ej: TRN-001)',
            
            'marca.required' => 'La marca es obligatoria',
            'marca.regex' => 'La marca solo puede contener letras, números y guiones',
            
            'categoria.required' => 'La categoría es obligatoria',
            'categoria.in' => 'La categoría debe ser: Premium, Clásico, Moderno o Infantil',
            
            'talla.required' => 'La talla es obligatoria',
            'talla.in' => 'La talla debe ser: S, M, L, XL o XXL',
            
            'color.required' => 'El color es obligatorio',
            'color.regex' => 'El color solo puede contener letras y espacios',
            
            'precio_alquiler.required' => 'El precio de alquiler es obligatorio',
            'precio_alquiler.numeric' => 'El precio debe ser un número válido',
            'precio_alquiler.min' => 'El precio debe ser mayor a 0',
            'precio_alquiler.max' => 'El precio no puede superar S/ 9,999.99',
            'precio_alquiler.regex' => 'El precio puede tener máximo 2 decimales',
            
            'estado.required' => 'El estado es obligatorio',
            'estado.in' => 'El estado debe ser: Disponible, Alquilado o Mantenimiento',
            
            'descripcion.max' => 'La descripción no puede superar los 1000 caracteres',
        ]);

        $terno = Terno::create($request->all());

        // Registrar actividad
        Actividad::create([
            'user_id' => auth()->id(),
            'tipo' => 'Terno Creado',
            'descripcion' => "Registró nuevo terno: {$terno->codigo} - {$terno->marca}",
            'valor' => $terno->precio_alquiler
        ]);

        return redirect()->route('ternos.index')->with('success', 'Terno creado exitosamente');
    }

    public function edit(Terno $terno)
    {
        return view('ternos.edit', compact('terno'));
    }

    public function update(Request $request, Terno $terno)
    {
        // ========== VALIDACIONES MEJORADAS ==========
        $request->validate([
            'codigo' => [
                'required',
                'string',
                'max:50',
                'unique:ternos,codigo,' . $terno->id,  // Permite el código actual
                'regex:/^[A-Z0-9\-]+$/'
            ],
            'marca' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s\-\.]+$/'
            ],
            'categoria' => [
                'required',
                'in:Premium,Clásico,Moderno,Infantil'
            ],
            'talla' => [
                'required',
                'in:S,M,L,XL,XXL'
            ],
            'color' => [
                'required',
                'string',
                'max:100',
                'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'
            ],
            'precio_alquiler' => [
                'required',
                'numeric',
                'min:0',
                'max:9999.99',
                'regex:/^\d+(\.\d{1,2})?$/'
            ],
            'estado' => [
                'required',
                'in:Disponible,Alquilado,Mantenimiento'
            ],
            'descripcion' => [
                'nullable',
                'string',
                'max:1000'
            ],
        ], [
            'codigo.required' => 'El código es obligatorio',
            'codigo.unique' => 'Este código ya está siendo usado por otro terno',
            'codigo.regex' => 'El código solo puede contener letras mayúsculas, números y guiones',
            
            'marca.required' => 'La marca es obligatoria',
            'marca.regex' => 'La marca solo puede contener letras, números y guiones',
            
            'categoria.required' => 'La categoría es obligatoria',
            'categoria.in' => 'La categoría debe ser: Premium, Clásico, Moderno o Infantil',
            
            'talla.required' => 'La talla es obligatoria',
            'talla.in' => 'La talla debe ser: S, M, L, XL o XXL',
            
            'color.required' => 'El color es obligatorio',
            'color.regex' => 'El color solo puede contener letras y espacios',
            
            'precio_alquiler.required' => 'El precio de alquiler es obligatorio',
            'precio_alquiler.numeric' => 'El precio debe ser un número válido',
            'precio_alquiler.min' => 'El precio debe ser mayor a 0',
            'precio_alquiler.max' => 'El precio no puede superar S/ 9,999.99',
            'precio_alquiler.regex' => 'El precio puede tener máximo 2 decimales',
            
            'estado.required' => 'El estado es obligatorio',
            'estado.in' => 'El estado debe ser: Disponible, Alquilado o Mantenimiento',
        ]);

        $terno->update($request->all());

        return redirect()->route('ternos.index')->with('success', 'Terno actualizado exitosamente');
    }

    public function destroy(Terno $terno)
    {
        // Verificar si tiene reservas asociadas
        if ($terno->reservas()->count() > 0) {
            return redirect()->route('ternos.index')->with('error', 'No se puede eliminar el terno porque tiene reservas asociadas');
        }

        $terno->delete();
        return redirect()->route('ternos.index')->with('success', 'Terno eliminado exitosamente');
    }

    public function show(Terno $terno)
    {
        $reservas = $terno->reservas()->with('cliente')->orderBy('created_at', 'desc')->get();
        return view('ternos.show', compact('terno', 'reservas'));
    }
}