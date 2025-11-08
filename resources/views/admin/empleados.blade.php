@extends('layouts.app')

@section('title', 'Gestión de Empleados - TernoFit')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-users-cog text-purple-600"></i> Gestión de Empleados
        </h1>
        <p class="text-gray-600 mt-2">Monitorea el rendimiento de tu equipo</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($empleados as $empleado)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition">
                <!-- Header -->
                <div class="bg-gradient-to-r from-purple-500 to-indigo-600 p-6 text-white">
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 rounded-full bg-white/20 flex items-center justify-center text-2xl font-bold">
                            {{ substr($empleado->name, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="font-bold text-lg">{{ $empleado->name }}</h3>
                            <p class="text-sm opacity-90">{{ $empleado->email }}</p>
                        </div>
                    </div>
                </div>

                <!-- Estadísticas -->
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">
                                <i class="fas fa-tasks text-blue-500"></i> Actividades
                            </span>
                            <span class="font-bold text-gray-800">{{ $estadisticas[$empleado->id]['actividades'] }}</span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">
                                <i class="fas fa-dollar-sign text-green-500"></i> Generado
                            </span>
                            <span class="font-bold text-green-600">S/ {{ number_format($estadisticas[$empleado->id]['valor_generado'], 2) }}</span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">
                                <i class="fas fa-check-circle text-purple-500"></i> Completadas
                            </span>
                            <span class="font-bold text-purple-600">{{ $estadisticas[$empleado->id]['tareas_completadas'] }}</span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">
                                <i class="fas fa-clock text-orange-500"></i> Pendientes
                            </span>
                            <span class="font-bold text-orange-600">{{ $estadisticas[$empleado->id]['tareas_pendientes'] }}</span>
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t">
                        <p class="text-xs text-gray-500">Miembro desde {{ $empleado->created_at->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if($empleados->count() == 0)
        <div class="bg-white rounded-xl shadow-lg p-12 text-center">
            <i class="fas fa-users text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-500">No hay empleados registrados</p>
        </div>
    @endif
</div>
@endsection