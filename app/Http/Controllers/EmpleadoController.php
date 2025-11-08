<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tarea;

class EmpleadoController extends Controller
{
    public function tareas()
    {
        $user = auth()->user();
        $tareas = Tarea::where('asignado_a', $user->id)
            ->with('asignadoPor')
            ->orderBy('created_at', 'desc')
            ->get();

        $pendientes = $tareas->where('estado', 'pendiente')->count();
        $enProceso = $tareas->where('estado', 'en_proceso')->count();
        $completadas = $tareas->where('estado', 'completada')->count();

        return view('empleado.tareas', compact('tareas', 'pendientes', 'enProceso', 'completadas'));
    }

    public function actualizarTarea(Request $request, $id)
    {
        $tarea = Tarea::findOrFail($id);
        
        // Verificar que la tarea pertenece al usuario
        if ($tarea->asignado_a !== auth()->id()) {
            abort(403);
        }

        $tarea->update([
            'estado' => $request->estado
        ]);

        return redirect()->route('empleado.tareas')->with('success', 'Estado de tarea actualizado');
    }
}