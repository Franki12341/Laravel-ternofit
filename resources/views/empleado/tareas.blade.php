@extends('layouts.app')

@section('title', 'Mis Tareas - TernoFit')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-clipboard-list text-green-600"></i> Mis Tareas
        </h1>
        <p class="text-gray-600 mt-2">Gestiona tus tareas asignadas</p>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif

    <!-- Resumen -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-br from-gray-500 to-gray-600 text-white rounded-xl p-6">
            <p class="text-sm opacity-80">Pendientes</p>
            <p class="text-4xl font-bold mt-2">{{ $pendientes }}</p>
        </div>
        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 text-white rounded-xl p-6">
            <p class="text-sm opacity-80">En Proceso</p>
            <p class="text-4xl font-bold mt-2">{{ $enProceso }}</p>
        </div>
        <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-xl p-6">
            <p class="text-sm opacity-80">Completadas</p>
            <p class="text-4xl font-bold mt-2">{{ $completadas }}</p>
        </div>
    </div>

    <!-- Lista de tareas -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        @if($tareas->count() > 0)
            <div class="space-y-4">
                @foreach($tareas as $tarea)
                    <div class="border rounded-lg p-4
                        @if($tarea->prioridad == 'alta') border-l-4 border-red-500
                        @elseif($tarea->prioridad == 'media')
                        @else
                        @endif">
                        
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-800 text-lg">{{ $tarea->titulo }}</h3>
                                <p class="text-gray-600 mt-2">{{ $tarea->descripcion }}</p>
                                <div class="flex items-center space-x-4 mt-3 text-sm text-gray-500">
                                    <span><i class="fas fa-user-tie"></i> Asignado por: {{ $tarea->asignadoPor->name }}</span>
                                    @if($tarea->fecha_vencimiento)
                                        <span><i class="fas fa-calendar"></i> Vence: {{ $tarea->fecha_vencimiento->format('d/m/Y') }}</span>
                                    @endif
                                    <span class="px-2 py-1 rounded-full text-xs
                                        @if($tarea->prioridad == 'alta') bg-red-100
                                        @elseif($tarea->prioridad == 'media')
                                        @else text-green-800
                                        @endif">
                                        Prioridad: {{ ucfirst($tarea->prioridad) }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="ml-4">
                                <form method="POST" action="{{ route('empleado.tareas.actualizar', $tarea->id) }}">
                                    @csrf
                                    <select name="estado" onchange="this.form.submit()" 
                                        class="px-3 py-2 border rounded-lg focus:ring-2 focus:ring-green-500
                                        @if($tarea->estado == 'completada') bg-green-100
                                        @elseif($tarea->estado == 'en_proceso') text-yellow-800
                                        @else
                                        @endif">
                                        <option value="pendiente" {{ $tarea->estado == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                        <option value="en_proceso" {{ $tarea->estado == 'en_proceso' ? 'selected' : '' }}>En Proceso</option>
                                        <option value="completada" {{ $tarea->estado == 'completada' ? 'selected' : '' }}>Completada</option>
                                    </select>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 text-center py-12">
                <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i><br>
                No tienes tareas asignadas
            </p>
        @endif
    </div>
</div>
@endsection