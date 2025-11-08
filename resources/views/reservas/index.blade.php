@extends('layouts.app')

@section('title', 'Reservas - TernoFit')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                <i class="fas fa-calendar-check text-green-600"></i> Gestión de Reservas
            </h1>
            <p class="text-gray-600 mt-1">Administra los alquileres de ternos</p>
        </div>
        <a href="{{ route('reservas.create') }}" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition shadow-lg">
            <i class="fas fa-plus mr-2"></i>Nueva Reserva
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
        </div>
    @endif

    <!-- Filtros -->
    <div class="bg-white rounded-xl shadow-lg p-4 mb-6">
        <form method="GET" action="{{ route('reservas.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <select name="estado" class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                <option value="">Todos los estados</option>
                <option value="Pendiente" {{ request('estado') == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                <option value="Confirmada" {{ request('estado') == 'Confirmada' ? 'selected' : '' }}>Confirmada</option>
                <option value="Entregada" {{ request('estado') == 'Entregada' ? 'selected' : '' }}>Entregada</option>
                <option value="Devuelta" {{ request('estado') == 'Devuelta' ? 'selected' : '' }}>Devuelta</option>
                <option value="Cancelada" {{ request('estado') == 'Cancelada' ? 'selected' : '' }}>Cancelada</option>
            </select>
            
            <!-- NUEVO: Filtro por Método de Pago -->
            <select name="metodo_pago" class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                <option value="">Todos los métodos de pago</option>
                <option value="Efectivo" {{ request('metodo_pago') == 'Efectivo' ? 'selected' : '' }}>💵 Efectivo</option>
                <option value="Yape" {{ request('metodo_pago') == 'Yape' ? 'selected' : '' }}>📱 Yape</option>
                <option value="Plin" {{ request('metodo_pago') == 'Plin' ? 'selected' : '' }}>📱 Plin</option>
                <option value="Transferencia" {{ request('metodo_pago') == 'Transferencia' ? 'selected' : '' }}>🏦 Transferencia</option>
                <option value="Tarjeta" {{ request('metodo_pago') == 'Tarjeta' ? 'selected' : '' }}>💳 Tarjeta</option>
            </select>

            <div class="flex space-x-2">
                <button type="submit" class="flex-1 bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition">
                    <i class="fas fa-search mr-2"></i>Filtrar
                </button>
                <a href="{{ route('reservas.index') }}" class="flex-1 bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition text-center">
                    <i class="fas fa-times mr-2"></i>Limpiar
                </a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-green-600 to-green-700 text-white">
                    <tr>
                        <th class="px-6 py-4 text-left">#</th>
                        <th class="px-6 py-4 text-left">Cliente</th>
                        <th class="px-6 py-4 text-left">Terno</th>
                        <th class="px-6 py-4 text-left">Fecha Evento</th>
                        <th class="px-6 py-4 text-left">Devolución</th>
                        <th class="px-6 py-4 text-left">Monto</th>
                        <th class="px-6 py-4 text-left">Método Pago</th> <!-- NUEVO -->
                        <th class="px-6 py-4 text-left">Estado</th>
                        <th class="px-6 py-4 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($reservas as $reserva)
                        <tr class="hover:bg-green-50 transition">
                            <td class="px-6 py-4 font-semibold text-gray-800">#{{ $reserva->id }}</td>
                            <td class="px-6 py-4">
                                <p class="font-semibold">{{ $reserva->cliente->nombre }} {{ $reserva->cliente->apellido }}</p>
                                <p class="text-sm text-gray-500">DNI: {{ $reserva->cliente->dni }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-semibold">{{ $reserva->terno->codigo }}</p>
                                <p class="text-sm text-gray-500">{{ $reserva->terno->marca }}</p>
                            </td>
                            <td class="px-6 py-4">{{ $reserva->fecha_evento->format('d/m/Y') }}</td>
                            <td class="px-6 py-4">{{ $reserva->fecha_devolucion->format('d/m/Y') }}</td>
                            <td class="px-6 py-4">
                                <p class="font-bold text-green-600">S/ {{ number_format($reserva->monto_total, 2) }}</p>
                                @if($reserva->saldo > 0)
                                    <p class="text-xs text-orange-600">Saldo: S/ {{ number_format($reserva->saldo, 2) }}</p>
                                @endif
                            </td>
                            
                            <!-- NUEVO: Columna de Método de Pago -->
                            <td class="px-6 py-4">
                                @if($reserva->metodo_pago === 'Efectivo')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        💵 Efectivo
                                    </span>
                                @elseif($reserva->metodo_pago === 'Yape')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                        📱 Yape
                                    </span>
                                @elseif($reserva->metodo_pago === 'Plin')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        📱 Plin
                                    </span>
                                @elseif($reserva->metodo_pago === 'Transferencia')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                        🏦 Transferencia
                                    </span>
                                @elseif($reserva->metodo_pago === 'Tarjeta')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-pink-100 text-pink-800">
                                        💳 Tarjeta
                                    </span>
                                @else
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        {{ $reserva->metodo_pago ?? 'No especificado' }}
                                    </span>
                                @endif
                            </td>
                            
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    @if($reserva->estado == 'Confirmada') bg-green-100
                                    @elseif($reserva->estado == 'Pendiente') text-yellow-800
                                    @elseif($reserva->estado == 'Entregada')
                                    @elseif($reserva->estado == 'Devuelta')
                                    @else
                                    @endif">
                                    {{ $reserva->estado }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center space-x-2">
                                    <a href="{{ route('reservas.show', $reserva) }}" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 transition" title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('reservas.edit', $reserva) }}" class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 transition" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('reservas.destroy', $reserva) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de eliminar esta reserva?\n\nEsta acción no se puede deshacer.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-calendar-times text-6xl text-gray-300 mb-4"></i>
                                <p class="text-lg font-semibold mb-2">No hay reservas registradas</p>
                                <a href="{{ route('reservas.create') }}" class="text-green-600 hover:underline mt-2 inline-block">
                                    <i class="fas fa-plus mr-1"></i>Crear la primera reserva
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Estadísticas rápidas -->
    @if($reservas->count() > 0)
        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg p-4 shadow-lg">
                <p class="text-sm opacity-90">Total de Reservas</p>
                <p class="text-3xl font-bold">{{ $reservas->total() }}</p>
            </div>
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg p-4 shadow-lg">
                <p class="text-sm opacity-90">Ingresos del Mes</p>
                <p class="text-3xl font-bold">S/ {{ number_format($reservas->sum('monto_total'), 2) }}</p>
            </div>
            <div class="bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-lg p-4 shadow-lg">
                <p class="text-sm opacity-90">Saldo Pendiente</p>
                <p class="text-3xl font-bold">S/ {{ number_format($reservas->sum('saldo'), 2) }}</p>
            </div>
        </div>
    @endif

    <div class="mt-6">
        {{ $reservas->links() }}
    </div>
</div>
@endsection