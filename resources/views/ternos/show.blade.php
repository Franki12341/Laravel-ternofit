@extends('layouts.app')

@section('title', 'Detalle del Terno - TernoFit')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('ternos.index') }}" class="text-purple-600 hover:underline">
            <i class="fas fa-arrow-left mr-2"></i>Volver a Ternos
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Información del Terno -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-br 
                    @if($terno->categoria == 'Premium') from-yellow-500 to-yellow-600
                    @elseif($terno->categoria == 'Moderno') from-purple-500 to-purple-600
                    @elseif($terno->categoria == 'Infantil') from-pink-500 to-pink-600
                    @else from-gray-700 to-gray-800
                    @endif
                    p-8 text-white text-center">
                    <i class="fas fa-vest text-6xl mb-4"></i>
                    <h2 class="text-3xl font-bold">{{ $terno->codigo }}</h2>
                    <p class="text-lg opacity-90 mt-2">{{ $terno->categoria }}</p>
                </div>

                <div class="p-6 space-y-4">
                    <div>
                        <label class="text-sm font-semibold text-gray-600">Marca</label>
                        <p class="text-lg text-gray-800"><i class="fas fa-tag text-purple-600 mr-2"></i>{{ $terno->marca }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-600">Talla</label>
                        <p class="text-lg text-gray-800"><i class="fas fa-ruler text-blue-600 mr-2"></i>{{ $terno->talla }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-600">Color</label>
                        <p class="text-lg text-gray-800"><i class="fas fa-palette text-pink-600 mr-2"></i>{{ $terno->color }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-600">Precio de Alquiler</label>
                        <p class="text-2xl font-bold text-green-600"><i class="fas fa-dollar-sign"></i> S/ {{ number_format($terno->precio_alquiler, 2) }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-600">Estado</label>
                        <p class="mt-1">
                            <span class="px-4 py-2 rounded-full text-sm font-semibold inline-block
                                @if($terno->estado == 'Disponible') bg-green-100 text-green-800
                                @elseif($terno->estado == 'Alquilado') bg-red-100 text-red-800
                                @else bg-yellow-100 text-yellow-800
                                @endif">
                                {{ $terno->estado }}
                            </span>
                        </p>
                    </div>

                    @if($terno->descripcion)
                    <div>
                        <label class="text-sm font-semibold text-gray-600">Descripción</label>
                        <p class="text-gray-800 mt-1">{{ $terno->descripcion }}</p>
                    </div>
                    @endif

                    <div class="pt-4 border-t">
                        <label class="text-sm font-semibold text-gray-600">Registrado</label>
                        <p class="text-sm text-gray-800">{{ $terno->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>

                <div class="p-6 bg-gray-50 flex space-x-2">
                    <a href="{{ route('ternos.edit', $terno) }}" class="flex-1 bg-yellow-500 text-white py-2 rounded-lg hover:bg-yellow-600 transition text-center">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                    <form action="{{ route('ternos.destroy', $terno) }}" method="POST" class="flex-1" onsubmit="return confirm('¿Estás seguro de eliminar este terno?')">
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
                    <i class="fas fa-history text-indigo-600"></i> Historial de Reservas
                </h3>

                @if($reservas->count() > 0)
                    <div class="space-y-4">
                        @foreach($reservas as $reserva)
                            <div class="border-l-4 
                                @if($reserva->estado == 'Confirmada') border-green-500 bg-green-50
                                @elseif($reserva->estado == 'Pendiente') border-yellow-500 bg-yellow-50
                                @elseif($reserva->estado == 'Entregada') border-blue-500 bg-blue-50
                                @elseif($reserva->estado == 'Devuelta') border-gray-500 bg-gray-50
                                @else border-red-500 bg-red-50
                                @endif
                                p-4 rounded-r-lg">
                                
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-800">Reserva #{{ $reserva->id }}</h4>
                                        <p class="text-sm text-gray-600 mt-1">
                                            <i class="fas fa-user mr-1"></i>
                                            Cliente: {{ $reserva->cliente->nombre }} {{ $reserva->cliente->apellido }}
                                        </p>
                                        <div class="mt-2 text-sm text-gray-600">
                                            <p><i class="fas fa-calendar mr-1"></i>Evento: {{ $reserva->fecha_evento->format('d/m/Y') }}</p>
                                            <p><i class="fas fa-calendar-check mr-1"></i>Devolución: {{ $reserva->fecha_devolucion->format('d/m/Y') }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="text-right ml-4">
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                                            @if($reserva->estado == 'Confirmada') bg-green-200 text-green-800
                                            @elseif($reserva->estado == 'Pendiente') bg-yellow-200 text-yellow-800
                                            @elseif($reserva->estado == 'Entregada') bg-blue-200 text-blue-800
                                            @elseif($reserva->estado == 'Devuelta') bg-gray-200 text-gray-800
                                            @else bg-red-200 text-red-800
                                            @endif">
                                            {{ $reserva->estado }}
                                        </span>
                                        <p class="text-lg font-bold text-green-600 mt-2">S/ {{ number_format($reserva->monto_total, 2) }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-calendar-times text-6xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">Este terno no tiene reservas aún</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection