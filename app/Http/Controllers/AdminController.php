<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Tarea;
use App\Models\Actividad;

class AdminController extends Controller
{
    public function empleados()
    {
        $empleados = User::where('rol', 'empleado')->get();
        
        // Estadísticas por empleado
        $estadisticas = [];
        foreach ($empleados as $empleado) {
            $estadisticas[$empleado->id] = [
                'actividades' => Actividad::where('user_id', $empleado->id)->count(),
                'valor_generado' => Actividad::where('user_id', $empleado->id)->sum('valor'),
                'tareas_completadas' => Tarea::where('asignado_a', $empleado->id)
                    ->where('estado', 'completada')->count(),
                'tareas_pendientes' => Tarea::where('asignado_a', $empleado->id)
                    ->where('estado', 'pendiente')->count(),
            ];
        }

        return view('admin.empleados', compact('empleados', 'estadisticas'));
    }

    public function asignarTareas()
    {
        $empleados = User::where('rol', 'empleado')->get();
        $tareas = Tarea::with(['asignadoA', 'asignadoPor'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.asignar-tareas', compact('empleados', 'tareas'));
    }

    public function storeTarea(Request $request)
    {
        $request->validate([
            'asignado_a' => 'required|exists:users,id',
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'prioridad' => 'required|in:baja,media,alta',
            'fecha_vencimiento' => 'nullable|date',
        ]);

        Tarea::create([
            'asignado_por' => auth()->id(),
            'asignado_a' => $request->asignado_a,
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'prioridad' => $request->prioridad,
            'fecha_vencimiento' => $request->fecha_vencimiento,
        ]);

        return redirect()->route('admin.asignar-tareas')->with('success', 'Tarea asignada correctamente');
    }
}