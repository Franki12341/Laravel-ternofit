<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Actividad;

class PerfilController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return view('perfil.index', compact('user'));
    }

    public function estadisticas()
    {
        $user = auth()->user();
        $actividades = Actividad::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        $totalActividades = Actividad::where('user_id', $user->id)->count();
        $valorTotal = Actividad::where('user_id', $user->id)->sum('valor');

        return view('perfil.estadisticas', compact('user', 'actividades', 'totalActividades', 'valorTotal'));
    }
}