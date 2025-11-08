@extends('layouts.app')

@section('title', 'Detalle de Reserva - TernoFit')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('reservas.index') }}" class="text-green-600 hover:underline">
            <i class="fas fa-arrow-left mr-2"></i>Volver a Reservas
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-green-600 to-green-700 p-8 text-white">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold">Reserva #{{ $reserva->id }}</h1>
                    <p class="text-green-100 mt-2">Fecha de reserva: {{ $reserva->fecha_reserva->format('d/m/Y') }}</p>
                </div>
                <div>
                    <span class="px-6 py-3 rounded-full text-lg font-semibold
                        @if($reserva->estado == 'Confirmada') bg-green-200 text-green-900
                        @elseif($reserva->estado == 'Pendiente') bg-yellow-200 text-yellow-900
                        @elseif($reserva->estado == 'Entregada') bg-blue-200 text-blue-900
                        @elseif($reserva->estado == 'Devuelta') bg-gray-200 text-gray-900
                        @else bg-red-200 text-red-900
                        @endif">
                        {{ $reserva->estado }}
                    </span>
                </div>
            </div>
        </div>

        <div class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Información del Cliente -->
                <div class="bg-blue-50 rounded-lg p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-user text-blue-600"></i> Información del Cliente
                    </h3>
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm font-semibold text-gray-600">Nombre Completo</label>
                            <p class="text-lg text-gray-800">{{ $reserva->cliente->nombre }} {{ $reserva->cliente->apellido }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-600">DNI</label>
                            <p class="text-gray-800">{{ $reserva->cliente->dni }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-600">Teléfono</label>
                            <p class="text-gray-800">{{ $reserva->cliente->telefono }}</p>
                        </div>
                        @if($reserva->cliente->email)
                        <div>
                            <label class="text-sm font-semibold text-gray-600">Email</label>
                            <p class="text-gray-800">{{ $reserva->cliente->email }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Información del Terno -->
                <div class="bg-purple-50 rounded-lg p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-vest text-purple-600"></i> Información del Terno
                    </h3>
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm font-semibold text-gray-600">Código</label>
                            <p class="text-lg text-gray-800">{{ $reserva->terno->codigo }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-600">Marca</label>
                            <p class="text-gray-800">{{ $reserva->terno->marca }}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-semibold text-gray-600">Talla</label>
                                <p class="text-gray-800">{{ $reserva->terno->talla }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-gray-600">Color</label>
                                <p class="text-gray-800">{{ $reserva->terno->color }}</p>
                            </div>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-600">Categoría</label>
                            <p class="text-gray-800">{{ $reserva->terno->categoria }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fechas -->
            <div class="mt-8 bg-orange-50 rounded-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-calendar text-orange-600"></i> Fechas Importantes
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <i class="fas fa-calendar-plus text-3xl text-blue-600 mb-2"></i>
                        <label class="block text-sm font-semibold text-gray-600">Fecha de Reserva</label>
                        <p class="text-lg font-bold text-gray-800">{{ $reserva->fecha_reserva->format('d/m/Y') }}</p>
                    </div>
                    <div class="text-center">
                        <i class="fas fa-calendar-day text-3xl text-green-600 mb-2"></i>
                        <label class="block text-sm font-semibold text-gray-600">Fecha del Evento</label>
                        <p class="text-lg font-bold text-gray-800">{{ $reserva->fecha_evento->format('d/m/Y') }}</p>
                    </div>
                    <div class="text-center">
                        <i class="fas fa-calendar-check text-3xl text-purple-600 mb-2"></i>
                        <label class="block text-sm font-semibold text-gray-600">Fecha de Devolución</label>
                        <p class="text-lg font-bold text-gray-800">{{ $reserva->fecha_devolucion->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Información Financiera -->
            <div class="mt-8 bg-green-50 rounded-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-dollar-sign text-green-600"></i> Información Financiera
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <label class="text-sm font-semibold text-gray-600">Monto Total</label>
                        <p class="text-3xl font-bold text-green-600">S/ {{ number_format($reserva->monto_total, 2) }}</p>
                    </div>
                    <div class="text-center">
                        <label class="text-sm font-semibold text-gray-600">Adelanto</label>
                        <p class="text-3xl font-bold text-blue-600">S/ {{ number_format($reserva->adelanto, 2) }}</p>
                    </div>
                    <div class="text-center">
                        <label class="text-sm font-semibold text-gray-600">Saldo Pendiente</label>
                        <p class="text-3xl font-bold text-orange-600">S/ {{ number_format($reserva->saldo, 2) }}</p>
                    </div>
                </div>
            </div>

            <!-- Observaciones -->
            @if($reserva->observaciones)
            <div class="mt-8 bg-gray-50 rounded-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-comment text-gray-600"></i> Observaciones
                </h3>
                <p class="text-gray-700">{{ $reserva->observaciones }}</p>
            </div>
            @endif

            <!-- Acciones -->
            <div class="mt-8 flex space-x-4">
                <a href="{{ route('reservas.edit', $reserva) }}" class="flex-1 bg-yellow-500 text-white py-3 rounded-lg hover:bg-yellow-600 transition text-center font-semibold">
                    <i class="fas fa-edit mr-2"></i>Editar Reserva
                </a>
                <form action="{{ route('reservas.destroy', $reserva) }}" method="POST" class="flex-1" onsubmit="return confirm('¿Estás seguro de eliminar esta reserva?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full bg-red-500 text-white py-3 rounded-lg hover:bg-red-600 transition font-semibold">
                        <i class="fas fa-trash mr-2"></i>Eliminar Reserva
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection