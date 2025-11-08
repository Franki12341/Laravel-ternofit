@extends('layouts.app')

@section('title', 'Mis Estadísticas - TernoFit')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-chart-line text-blue-600"></i> Mis Estadísticas
        </h1>
        <p class="text-gray-600 mt-2">Rendimiento y actividad personal</p>
    </div>

    <!-- Tarjetas de métricas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-80">Total Actividades</p>
                    <p class="text-4xl font-bold mt-2">{{ $totalActividades }}</p>
                </div>
                <i class="fas fa-tasks text-5xl opacity-30"></i>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-80">Valor Generado</p>
                    <p class="text-4xl font-bold mt-2">S/ {{ number_format($valorTotal, 2) }}</p>
                </div>
                <i class="fas fa-dollar-sign text-5xl opacity-30"></i>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-80">Promedio Mensual</p>
                    <p class="text-4xl font-bold mt-2">{{ $totalActividades > 0 ? round($totalActividades / max(1, $user->created_at->diffInMonths(now()))) : 0 }}</p>
                </div>
                <i class="fas fa-chart-bar text-5xl opacity-30"></i>
            </div>
        </div>
    </div>

    <!-- Actividades recientes -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Actividades Recientes</h2>
        
        @if($actividades->count() > 0)
            <div class="space-y-3">
                @foreach($actividades as $actividad)
                    <div class="border-l-4 border-blue-500 bg-blue-50 p-4 rounded-r-lg">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-semibold text-gray-800">{{ $actividad->tipo }}</p>
                                <p class="text-sm text-gray-600 mt-1">{{ $actividad->descripcion }}</p>
                            </div>
                            <div class="text-right">
                                @if($actividad->valor)
                                    <p class="text-green-600 font-bold">S/ {{ number_format($actividad->valor, 2) }}</p>
                                @endif
                                <p class="text-xs text-gray-500 mt-1">{{ $actividad->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 text-center py-8">No hay actividades registradas aún</p>
        @endif
    </div>
</div>
@endsection