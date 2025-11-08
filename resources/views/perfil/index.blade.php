@extends('layouts.app')

@section('title', 'Mi Perfil - TernoFit')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <!-- Header del perfil -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-8 text-white">
            <div class="flex items-center space-x-6">
                <div class="w-24 h-24 rounded-full bg-white/20 flex items-center justify-center text-4xl font-bold">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <div>
                    <h1 class="text-3xl font-bold">{{ $user->name }}</h1>
                    <p class="text-blue-100 mt-1">{{ $user->email }}</p>
                    <p class="mt-2">
                        @if($user->isAdmin())
                            <span class="bg-yellow-400 text-gray-900 px-3 py-1 rounded-full text-sm font-semibold">
                                <i class="fas fa-crown"></i> Administrador
                            </span>
                        @else
                            <span class="bg-white/20 px-3 py-1 rounded-full text-sm font-semibold">
                                <i class="fas fa-user"></i> Empleado
                            </span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Información del perfil -->
        <div class="p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Información Personal</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-sm font-semibold text-gray-600">Nombre Completo</label>
                    <p class="text-lg text-gray-800 mt-1">{{ $user->name }}</p>
                </div>
                <div>
                    <label class="text-sm font-semibold text-gray-600">Email</label>
                    <p class="text-lg text-gray-800 mt-1">{{ $user->email }}</p>
                </div>
                <div>
                    <label class="text-sm font-semibold text-gray-600">Rol</label>
                    <p class="text-lg text-gray-800 mt-1 capitalize">{{ $user->rol }}</p>
                </div>
                <div>
                    <label class="text-sm font-semibold text-gray-600">Miembro desde</label>
                    <p class="text-lg text-gray-800 mt-1">{{ $user->created_at->format('d/m/Y') }}</p>
                </div>
            </div>

            <div class="mt-8 flex space-x-4">
                <a href="{{ route('mis-estadisticas') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-chart-line mr-2"></i>Ver Mis Estadísticas
                </a>
            </div>
        </div>
    </div>
</div>
@endsection