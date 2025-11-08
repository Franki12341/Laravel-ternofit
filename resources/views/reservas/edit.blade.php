@extends('layouts.app')

@section('title', 'Editar Reserva - TernoFit')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('reservas.index') }}" class="text-green-600 hover:underline">
            <i class="fas fa-arrow-left mr-2"></i>Volver a Reservas
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">
            <i class="fas fa-edit text-green-600"></i> Editar Reserva #{{ $reserva->id }}
        </h1>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                <p class="font-bold mb-2">❌ Por favor corrige los siguientes errores:</p>
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Información del Cliente y Terno (No editable) -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-800 mb-3">
                <i class="fas fa-info-circle text-blue-600"></i> Información de la Reserva
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Cliente:</p>
                    <p class="font-semibold text-gray-800">{{ $reserva->cliente->nombre }} {{ $reserva->cliente->apellido }}</p>
                    <p class="text-sm text-gray-500">DNI: {{ $reserva->cliente->dni }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Terno:</p>
                    <p class="font-semibold text-gray-800">{{ $reserva->terno->codigo }} - {{ $reserva->terno->marca }}</p>
                    <p class="text-sm text-gray-500">{{ $reserva->terno->categoria }} | {{ $reserva->terno->talla }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Fecha del Evento:</p>
                    <p class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($reserva->fecha_evento)->format('d/m/Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Fecha de Devolución:</p>
                    <p class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($reserva->fecha_devolucion)->format('d/m/Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Monto Total:</p>
                    <p class="font-semibold text-green-600 text-lg">S/ {{ number_format($reserva->monto_total, 2) }}</p>
                </div>
            </div>
        </div>

        <form action="{{ route('reservas.update', $reserva) }}" method="POST" id="formReserva">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Estado -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-flag text-green-600"></i> Estado *
                    </label>
                    <select 
                        name="estado" 
                        id="estado"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('estado') @enderror"
                        required
                    >
                        <option value="Pendiente" {{ old('estado', $reserva->estado) == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="Confirmada" {{ old('estado', $reserva->estado) == 'Confirmada' ? 'selected' : '' }}>Confirmada</option>
                        <option value="Entregada" {{ old('estado', $reserva->estado) == 'Entregada' ? 'selected' : '' }}>Entregada</option>
                        <option value="Devuelta" {{ old('estado', $reserva->estado) == 'Devuelta' ? 'selected' : '' }}>Devuelta</option>
                        <option value="Cancelada" {{ old('estado', $reserva->estado) == 'Cancelada' ? 'selected' : '' }}>Cancelada</option>
                    </select>
                    @error('estado')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Adelanto -->
<div>
    <label class="block text-sm font-semibold text-gray-700 mb-2">
        <i class="fas fa-money-bill-wave text-green-600"></i> Adelanto (S/) *
    </label>
    <input 
        type="number" 
        step="0.01" 
        name="adelanto" 
        id="adelanto" 
        value="{{ old('adelanto', $reserva->adelanto) }}" 
        min="0"
        max="{{ $reserva->monto_total }}"
        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('adelanto') @enderror" 
        required
    >
    <p class="text-xs text-gray-500 mt-1">Máximo: S/ {{ number_format($reserva->monto_total, 2) }}</p>
    @error('adelanto')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

<!-- ⬇️⬇️⬇️ AGREGAR ESTO NUEVO ⬇️⬇️⬇️ -->

<!-- Método de Pago -->
<div>
    <label class="block text-sm font-semibold text-gray-700 mb-2">
        <i class="fas fa-credit-card text-green-600"></i> Método de Pago *
    </label>
    <select 
        name="metodo_pago" 
        id="metodo_pago"
        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('metodo_pago') @enderror"
        required
    >
        <option value="">Seleccionar método</option>
        <option value="Efectivo" {{ old('metodo_pago', $reserva->metodo_pago) == 'Efectivo' ? 'selected' : '' }}>
            💵 Efectivo
        </option>
        <option value="Yape" {{ old('metodo_pago', $reserva->metodo_pago) == 'Yape' ? 'selected' : '' }}>
            📱 Yape
        </option>
        <option value="Plin" {{ old('metodo_pago', $reserva->metodo_pago) == 'Plin' ? 'selected' : '' }}>
            📱 Plin
        </option>
        <option value="Transferencia" {{ old('metodo_pago', $reserva->metodo_pago) == 'Transferencia' ? 'selected' : '' }}>
            🏦 Transferencia Bancaria
        </option>
        <option value="Tarjeta" {{ old('metodo_pago', $reserva->metodo_pago) == 'Tarjeta' ? 'selected' : '' }}>
            💳 Tarjeta de Crédito/Débito
        </option>
    </select>
    @error('metodo_pago')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>


                <!-- Observaciones -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-comment text-green-600"></i> Observaciones (opcional)
                    </label>
                    <textarea 
                        name="observaciones" 
                        rows="3" 
                        maxlength="1000"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('observaciones') @enderror"
                    >{{ old('observaciones', $reserva->observaciones) }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Máximo 1000 caracteres</p>
                    @error('observaciones')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Resumen Actualizado -->
            <div class="mt-6 bg-green-50 border border-green-200 rounded-lg p-6">
                <h3 class="font-bold text-gray-800 mb-4">💰 Resumen Actualizado</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Monto Total</p>
                        <p class="text-lg font-bold text-gray-800">S/ {{ number_format($reserva->monto_total, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Adelanto Actual</p>
                        <p class="text-lg font-bold text-blue-600" id="adelanto_mostrar">S/ {{ number_format($reserva->adelanto, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Saldo Pendiente</p>
                        <p class="text-lg font-bold text-orange-600" id="saldo_mostrar">S/ {{ number_format($reserva->saldo, 2) }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex space-x-4">
                <button 
                    type="submit" 
                    class="bg-green-600 text-white px-8 py-3 rounded-lg hover:bg-green-700 transition font-semibold">
                    <i class="fas fa-save mr-2"></i>Actualizar Reserva
                </button>
                <a 
                    href="{{ route('reservas.index') }}" 
                    class="bg-gray-500 text-white px-8 py-3 rounded-lg hover:bg-gray-600 transition font-semibold">
                    <i class="fas fa-times mr-2"></i>Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<script>
const montoTotal = {{ $reserva->monto_total }};

// ========== VALIDACIÓN DE ADELANTO EN TIEMPO REAL ==========
document.getElementById('adelanto').addEventListener('input', function(e) {
    let value = this.value;
    
    // Permitir solo números y punto decimal
    value = value.replace(/[^\d.]/g, '');
    
    // Solo un punto decimal
    const parts = value.split('.');
    if (parts.length > 2) {
        value = parts[0] + '.' + parts.slice(1).join('');
    }
    
    // Máximo 2 decimales
    if (parts[1] && parts[1].length > 2) {
        value = parts[0] + '.' + parts[1].substring(0, 2);
    }
    
    this.value = value;
    
    // Actualizar resumen
    const adelanto = parseFloat(value) || 0;
    const saldo = montoTotal - adelanto;
    
    document.getElementById('adelanto_mostrar').textContent = 'S/ ' + adelanto.toFixed(2);
    document.getElementById('saldo_mostrar').textContent = 'S/ ' + saldo.toFixed(2);
    
    // Cambiar color si es negativo
    if (saldo < 0) {
        document.getElementById('saldo_mostrar').classList.remove('text-orange-600');
        document.getElementById('saldo_mostrar').classList.add('text-red-600');
    } else {
        document.getElementById('saldo_mostrar').classList.remove('text-red-600');
        document.getElementById('saldo_mostrar').classList.add('text-orange-600');
    }
});

// ========== VALIDACIÓN ANTES DE ENVIAR ==========
document.getElementById('formReserva').addEventListener('submit', function(e) {
    const adelanto = parseFloat(document.getElementById('adelanto').value) || 0;
    
    if (adelanto < 0) {
        e.preventDefault();
        alert('❌ El adelanto no puede ser negativo');
        return false;
    }
    
    if (adelanto > montoTotal) {
        e.preventDefault();
        alert(`❌ El adelanto (S/ ${adelanto.toFixed(2)}) no puede ser mayor que el monto total (S/ ${montoTotal.toFixed(2)})`);
        return false;
    }
});
</script>
@endsection