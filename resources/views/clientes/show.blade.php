@extends('layouts.app')

@section('title', 'Detalle del Cliente - TernoFit')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('clientes.index') }}" class="text-blue-600 hover:underline">
            <i class="fas fa-arrow-left mr-2"></i>Volver a Clientes
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Información del Cliente -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-6 text-white">
                    <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-user text-4xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-center">{{ $cliente->nombre }} {{ $cliente->apellido }}</h2>
                    <p class="text-center text-blue-100 mt-1">Cliente</p>
                </div>

                <div class="p-6 space-y-4">
                    <div>
                        <label class="text-sm font-semibold text-gray-600">DNI</label>
                        <p class="text-lg text-gray-800"><i class="fas fa-id-card text-blue-600 mr-2"></i>{{ $cliente->dni }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-600">Teléfono</label>
                        <p class="text-lg text-gray-800"><i class="fas fa-phone text-green-600 mr-2"></i>{{ $cliente->telefono }}</p>
                    </div>

                    @if($cliente->email)
                    <div>
                        <label class="text-sm font-semibold text-gray-600">Email</label>
                        <p class="text-lg text-gray-800"><i class="fas fa-envelope text-purple-600 mr-2"></i>{{ $cliente->email }}</p>
                    </div>
                    @endif

                    @if($cliente->direccion)
                    <div>
                        <label class="text-sm font-semibold text-gray-600">Dirección</label>
                        <p class="text-lg text-gray-800"><i class="fas fa-map-marker-alt text-red-600 mr-2"></i>{{ $cliente->direccion }}</p>
                    </div>
                    @endif

                    <div class="pt-4 border-t">
                        <label class="text-sm font-semibold text-gray-600">Registrado</label>
                        <p class="text-sm text-gray-800">{{ $cliente->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>

                <div class="p-6 bg-gray-50 flex space-x-2">
                    <a href="{{ route('clientes.edit', $cliente) }}" class="flex-1 bg-yellow-500 text-white py-2 rounded-lg hover:bg-yellow-600 transition text-center">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                    <form action="{{ route('clientes.destroy', $cliente) }}" method="POST" class="flex-1" onsubmit="return confirm('¿Estás seguro de eliminar este cliente?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full bg-red-500 text-white py-2 rounded-lg hover:bg-red-600 transition">
                            <i class="fas fa-trash"></i> Eliminar
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Historial de Reservas -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-2xl font-bold text-gray-800 mb-6">
                    <i class="fas fa-history text-purple-600"></i> Historial de Reservas
                </h3>

                @if($reservas->count() > 0)
                    <div class="space-y-4">
                        @foreach($reservas as $reserva)
                            <div class="border-l-4 
                                @if($reserva->estado == 'Confirmada') border-green-500
                                @elseif($reserva->estado == 'Pendiente') bg-yellow-50
                                @elseif($reserva->estado == 'Entregada')
                                @elseif($reserva->estado == 'Devuelta')
                                @else
                                @endif
                                p-4 rounded-r-lg">
                                
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-800">Reserva #{{ $reserva->id }}</h4>
                                        <p class="text-sm text-gray-600 mt-1">
                                            <i class="fas fa-vest mr-1"></i>
                                            Terno: {{ $reserva->terno->codigo }} - {{ $reserva->terno->marca }}
                                        </p>
                                        <div class="mt-2 text-sm text-gray-600">
                                            <p><i class="fas fa-calendar mr-1"></i>Evento: {{ $reserva->fecha_evento->format('d/m/Y') }}</p>
                                            <p><i class="fas fa-calendar-check mr-1"></i>Devolución: {{ $reserva->fecha_devolucion->format('d/m/Y') }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="text-right ml-4">
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                                            @if($reserva->estado == 'Confirmada') bg-green-200
                                            @elseif($reserva->estado == 'Pendiente')
                                            @elseif($reserva->estado == 'Entregada') text-blue-800
                                            @elseif($reserva->estado == 'Devuelta')
                                            @else
                                            @endif">
                                            {{ $reserva->estado }}
                                        </span>
                                        <p class="text-lg font-bold text-green-600 mt-2">S/ {{ number_format($reserva->monto_total, 2) }}</p>
                                        @if($reserva->saldo > 0)
                                            <p class="text-sm text-orange-600">Saldo: S/ {{ number_format($reserva->saldo, 2) }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-calendar-times text-6xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">Este cliente no tiene reservas aún</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection