@extends('layouts.app')

@section('title', 'Dashboard - TernoFit')

@section('content')
<div class="min-h-screen">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-2xl p-12 mb-8 shadow-2xl">
        <div class="text-center">
            <h1 class="text-5xl font-bold mb-4">
                <i class="fas fa-user-tie mr-3"></i>TernoFit
            </h1>
            <p class="text-xl opacity-90">Sistema de Gestión de Alquiler de Ternos</p>
            <p class="text-lg mt-2 opacity-80">Elegancia y profesionalismo para cada ocasión</p>
        </div>
    </div>

    <!-- Módulos principales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Clientes -->
        <a href="{{ route('clientes.index') }}" class="group">
            <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                <div class="text-center">
                    <div class="bg-blue-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-blue-200 transition">
                        <i class="fas fa-users text-4xl text-blue-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">Clientes</h3>
                    <p class="text-gray-600">Gestiona tu cartera de clientes</p>
                </div>
            </div>
        </a>

        <!-- Ternos -->
        <a href="{{ route('ternos.index') }}" class="group">
            <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                <div class="text-center">
                    <div class="bg-purple-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-purple-200 transition">
                        <i class="fas fa-vest text-4xl text-purple-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">Ternos</h3>
                    <p class="text-gray-600">Administra tu inventario</p>
                </div>
            </div>
        </a>

        <!-- Reservas -->
        <a href="{{ route('reservas.index') }}" class="group">
            <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                <div class="text-center">
                    <div class="bg-green-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-green-200 transition">
                        <i class="fas fa-calendar-check text-4xl text-green-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">Reservas</h3>
                    <p class="text-gray-600">Control de alquileres</p>
                </div>
            </div>
        </a>

        <!-- Reportes -->
        <a href="{{ route('reportes.index') }}" class="group">
            <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                <div class="text-center">
                    <div class="bg-orange-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-orange-200 transition">
                        <i class="fas fa-chart-bar text-4xl text-orange-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">Reportes</h3>
                    <p class="text-gray-600">Análisis y estadísticas</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Estadísticas con datos reales -->
    <div class="mt-12 grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-80">Total Clientes</p>
                    <p class="text-3xl font-bold">{{ \App\Models\Cliente::count() }}</p>
                </div>
                <i class="fas fa-users text-4xl opacity-50"></i>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-80">Total Ternos</p>
                    <p class="text-3xl font-bold">{{ \App\Models\Terno::count() }}</p>
                </div>
                <i class="fas fa-vest text-4xl opacity-50"></i>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-80">Ternos Disponibles</p>
                    <p class="text-3xl font-bold">{{ \App\Models\Terno::where('estado', 'Disponible')->count() }}</p>
                </div>
                <i class="fas fa-check-circle text-4xl opacity-50"></i>
            </div>
        </div>

        <div class="bg-gradient-to-br from-orange-500 to-orange-600 text-white rounded-xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-80">Reservas Activas</p>
                    <p class="text-3xl font-bold">{{ \App\Models\Reserva::whereIn('estado', ['Confirmada', 'Entregada', 'Pendiente'])->count() }}</p>
                </div>
                <i class="fas fa-calendar-check text-4xl opacity-50"></i>
            </div>
        </div>
    </div>

    <!-- Últimas Reservas -->
    <div class="mt-12 bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">
            <i class="fas fa-clock text-indigo-600"></i> Últimas Reservas
        </h2>
        
        @php
            $ultimasReservas = \App\Models\Reserva::with(['cliente', 'terno'])
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
        @endphp

        @if($ultimasReservas->count() > 0)
            <div class="space-y-3">
                @foreach($ultimasReservas as $reserva)
                    <div class="border-l-4 
                        @if($reserva->estado == 'Confirmada') border-green-500
                        @elseif($reserva->estado == 'Pendiente') bg-yellow-50
                        @elseif($reserva->estado == 'Entregada')
                        @else
                        @endif
                        p-4 rounded-r-lg">
                        <div class="flex justify-between items-center">
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-800">Reserva #{{ $reserva->id }}</h4>
                                <p class="text-sm text-gray-600 mt-1">
                                    <i class="fas fa-user mr-1"></i>{{ $reserva->cliente->nombre }} {{ $reserva->cliente->apellido }}
                                    | <i class="fas fa-vest mr-1"></i>{{ $reserva->terno->codigo }}
                                    | <i class="fas fa-calendar mr-1"></i>{{ $reserva->fecha_evento->format('d/m/Y') }}
                                </p>
                            </div>
                            <div class="text-right ml-4">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    @if($reserva->estado == 'Confirmada') text-green-800
                                    @elseif($reserva->estado == 'Pendiente')
                                    @elseif($reserva->estado == 'Entregada') bg-blue-200
                                    @else
                                    @endif">
                                    {{ $reserva->estado }}
                                </span>
                                <p class="text-sm font-bold text-green-600 mt-1">S/ {{ number_format($reserva->monto_total, 2) }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-4 text-center">
                <a href="{{ route('reservas.index') }}" class="text-indigo-600 hover:underline">
                    Ver todas las reservas <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        @else
            <div class="text-center py-8">
                <i class="fas fa-calendar-times text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500">No hay reservas registradas aún</p>
                <a href="{{ route('reservas.create') }}" class="text-indigo-600 hover:underline mt-2 inline-block">
                    Crear la primera reserva
                </a>
            </div>
        @endif
    </div>
</div>
@endsection