@extends('layouts.app')

@section('title', 'Asignar Tareas - TernoFit')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-tasks text-indigo-600"></i> Asignar Tareas
        </h1>
        <p class="text-gray-600 mt-2">Gestiona las tareas de tu equipo</p>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Formulario -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Nueva Tarea</h2>
                
                <form method="POST" action="{{ route('admin.tareas.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Asignar a</label>
                        <select name="asignado_a" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500" required>
                            <option value="">Seleccionar empleado</option>
                            @foreach($empleados as $empleado)
                                <option value="{{ $empleado->id }}">{{ $empleado->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Título</label>
                        <input type="text" name="titulo" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Descripción</label>
                        <textarea name="descripcion" rows="3" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500" required></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Prioridad</label>
                        <select name="prioridad" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500" required>
                            <option value="baja">Baja</option>
                            <option value="media" selected>Media</option>
                            <option value="alta">Alta</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Fecha de vencimiento</label>
                        <input type="date" name="fecha_vencimiento" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700 transition">
                        <i class="fas fa-plus mr-2"></i>Asignar Tarea
                    </button>
                </form>
            </div>
        </div>

        <!-- Lista de tareas -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Tareas Asignadas</h2>
                
                @if($tareas->count() > 0)
                    <div class="space-y-3">
                        @foreach($tareas as $tarea)
                            <div class="border rounded-lg p-4 hover:shadow-md transition
                                @if($tarea->prioridad == 'alta') border-l-4
                                @elseif($tarea->prioridad == 'media')
                                @else border-green-500
                                @endif">
                                
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-800">{{ $tarea->titulo }}</h3>
                                        <p class="text-sm text-gray-600 mt-1">{{ $tarea->descripcion }}</p>
                                        <div class="flex items-center space-x-4 mt-2 text-sm text-gray-500">
                                            <span><i class="fas fa-user"></i> {{ $tarea->asignadoA->name }}</span>
                                            @if($tarea->fecha_vencimiento)
                                                <span><i class="fas fa-calendar"></i> {{ $tarea->fecha_vencimiento->format('d/m/Y') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                                            @if($tarea->estado == 'completada')
                                            @elseif($tarea->estado == 'en_proceso')
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ ucfirst(str_replace('_', ' ', $tarea->estado)) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">No hay tareas asignadas</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection