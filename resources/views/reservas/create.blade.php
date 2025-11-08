@extends('layouts.app')

@section('title', 'Nueva Reserva - TernoFit')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('reservas.index') }}" class="text-green-600 hover:underline">
            <i class="fas fa-arrow-left mr-2"></i>Volver a Reservas
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">
            <i class="fas fa-calendar-plus text-green-600"></i> Crear Nueva Reserva
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

        <form action="{{ route('reservas.store') }}" method="POST" id="reservaForm">
            @csrf

            <!-- ========== SECCIÓN: BÚSQUEDA DE CLIENTE ========== -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-user-search text-blue-600"></i> Datos del Cliente
                </h3>

                <!-- Campo de búsqueda -->
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-search text-blue-600"></i> Buscar por Nombre o DNI
                    </label>
                    <input 
                        type="text" 
                        id="buscarCliente" 
                        placeholder="Escribe el nombre o DNI del cliente..."
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                        autocomplete="off"
                    >
                    <div id="resultadosBusqueda" class="hidden mt-2 bg-white border rounded-lg shadow-lg max-h-60 overflow-y-auto"></div>
                </div>

                <!-- Cliente seleccionado -->
                <div id="clienteSeleccionado" class="hidden mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="font-semibold text-gray-800">Cliente seleccionado:</p>
                            <p id="clienteNombre" class="text-lg text-green-600"></p>
                            <p id="clienteDni" class="text-sm text-gray-600"></p>
                        </div>
                        <button type="button" id="cambiarCliente" class="text-blue-600 hover:underline text-sm">
                            <i class="fas fa-edit"></i> Cambiar
                        </button>
                    </div>
                </div>

                <!-- Input oculto para el ID del cliente -->
                <input type="hidden" name="cliente_id" id="cliente_id" required>

                <!-- Formulario de registro rápido -->
                <div id="registroRapido" class="hidden space-y-4">
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                        <p class="text-sm text-yellow-800">
                            <i class="fas fa-info-circle"></i> Cliente no encontrado. Complete los datos para registrarlo:
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">DNI *</label>
                            <input 
                                type="text" 
                                id="nuevo_dni" 
                                maxlength="8"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                                placeholder="12345678"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Teléfono *</label>
                            <input 
                                type="text" 
                                id="nuevo_telefono"
                                maxlength="9"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                                placeholder="987654321"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nombre *</label>
                            <input 
                                type="text" 
                                id="nuevo_nombre"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Apellido *</label>
                            <input 
                                type="text" 
                                id="nuevo_apellido"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                            >
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Email (opcional)</label>
                            <input 
                                type="email" 
                                id="nuevo_email"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                            >
                        </div>
                    </div>

                    <button 
                        type="button" 
                        id="btnRegistrarCliente"
                        class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-user-plus mr-2"></i>Registrar y Continuar
                    </button>
                </div>
            </div>

            <!-- ========== FORMULARIO DE RESERVA ========== -->
            <div id="formularioReserva" class="hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Terno -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-vest text-green-600"></i> Terno *
                        </label>
                        <select name="terno_id" id="terno_select" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('terno_id') @enderror" required>
                            <option value="">Seleccionar terno</option>
                            @foreach($ternos as $terno)
                                <option value="{{ $terno->id }}" data-precio="{{ $terno->precio_alquiler }}">
                                    {{ $terno->codigo }} - {{ $terno->marca }} ({{ $terno->categoria }}) - S/ {{ number_format($terno->precio_alquiler, 2) }}
                                </option>
                            @endforeach
                        </select>
                        @error('terno_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Fecha de Reserva -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-calendar text-green-600"></i> Fecha de Reserva *
                        </label>
                        <input 
                            type="date" 
                            name="fecha_reserva" 
                            id="fecha_reserva"
                            value="{{ old('fecha_reserva', date('Y-m-d')) }}" 
                            max="{{ date('Y-m-d') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('fecha_reserva') @enderror" 
                            required
                        >
                        <p class="text-xs text-gray-500 mt-1">No puede ser futura</p>
                        @error('fecha_reserva')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Fecha del Evento -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-calendar-day text-green-600"></i> Fecha del Evento *
                        </label>
                        <input 
                            type="date" 
                            name="fecha_evento" 
                            id="fecha_evento" 
                            value="{{ old('fecha_evento') }}"
                            min="{{ date('Y-m-d') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('fecha_evento') @enderror" 
                            required
                        >
                        <p class="text-xs text-gray-500 mt-1">No puede ser en el pasado</p>
                        @error('fecha_evento')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Fecha de Devolución -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-calendar-check text-green-600"></i> Fecha de Devolución *
                        </label>
                        <input 
                            type="date" 
                            name="fecha_devolucion" 
                            id="fecha_devolucion" 
                            value="{{ old('fecha_devolucion') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('fecha_devolucion') @enderror" 
                            required
                        >
                        <p class="text-xs text-gray-500 mt-1">Debe ser después del evento</p>
                        @error('fecha_devolucion')
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
        value="{{ old('adelanto', 0) }}" 
        min="0"
        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('adelanto') @enderror" 
        required
    >
    <p class="text-xs text-gray-500 mt-1" id="mensaje_adelanto">Mínimo: S/ 0.00</p>
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
        <option value="Efectivo" {{ old('metodo_pago') == 'Efectivo' ? 'selected' : '' }}>
            💵 Efectivo
        </option>
        <option value="Yape" {{ old('metodo_pago') == 'Yape' ? 'selected' : '' }}>
            📱 Yape
        </option>
        <option value="Plin" {{ old('metodo_pago') == 'Plin' ? 'selected' : '' }}>
            📱 Plin
        </option>
        <option value="Transferencia" {{ old('metodo_pago') == 'Transferencia' ? 'selected' : '' }}>
            🏦 Transferencia Bancaria
        </option>
        <option value="Tarjeta" {{ old('metodo_pago') == 'Tarjeta' ? 'selected' : '' }}>
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
                        >{{ old('observaciones') }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Máximo 1000 caracteres</p>
                        @error('observaciones')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Resumen de Costos -->
                <div class="mt-6 bg-green-50 border border-green-200 rounded-lg p-6">
                    <h3 class="font-bold text-gray-800 mb-4">💰 Resumen de Costos</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Precio/día</p>
                            <p class="text-lg font-bold text-gray-800" id="precio_dia">S/ 0.00</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Días</p>
                            <p class="text-lg font-bold text-gray-800" id="total_dias">0</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Total</p>
                            <p class="text-lg font-bold text-green-600" id="monto_total">S/ 0.00</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Saldo</p>
                            <p class="text-lg font-bold text-orange-600" id="saldo">S/ 0.00</p>
                        </div>
                    </div>
                </div>

                <!-- Botones -->
                <div class="mt-8 flex space-x-4">
                    <button type="submit" class="bg-green-600 text-white px-8 py-3 rounded-lg hover:bg-green-700 transition font-semibold">
                        <i class="fas fa-save mr-2"></i>Crear Reserva
                    </button>
                    <a href="{{ route('reservas.index') }}" class="bg-gray-500 text-white px-8 py-3 rounded-lg hover:bg-gray-600 transition font-semibold">
                        <i class="fas fa-times mr-2"></i>Cancelar
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
let clienteActual = null;
let timeout = null;
let montoTotalActual = 0;

// ========== VALIDACIONES EN TIEMPO REAL - NUEVO CLIENTE ==========
document.getElementById('nuevo_dni')?.addEventListener('input', function(e) {
    this.value = this.value.replace(/[^0-9]/g, '').slice(0, 8);
});

document.getElementById('nuevo_telefono')?.addEventListener('input', function(e) {
    this.value = this.value.replace(/[^0-9]/g, '').slice(0, 9);
});

document.getElementById('nuevo_nombre')?.addEventListener('input', function(e) {
    this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');
});

document.getElementById('nuevo_apellido')?.addEventListener('input', function(e) {
    this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');
});

// ========== BÚSQUEDA EN TIEMPO REAL ==========
document.getElementById('buscarCliente').addEventListener('input', function(e) {
    const termino = e.target.value.trim();
    
    clearTimeout(timeout);
    
    if (termino.length < 2) {
        document.getElementById('resultadosBusqueda').classList.add('hidden');
        return;
    }
    
    timeout = setTimeout(async () => {
        try {
            const response = await fetch(`{{ route('api.buscar-cliente') }}?termino=${encodeURIComponent(termino)}`);
            const clientes = await response.json();
            
            const resultados = document.getElementById('resultadosBusqueda');
            
            if (clientes.length > 0) {
                resultados.innerHTML = clientes.map(cliente => `
                    <div class="p-3 hover:bg-blue-50 cursor-pointer border-b last:border-b-0 cliente-item" data-cliente='${JSON.stringify(cliente)}'>
                        <p class="font-semibold text-gray-800">${cliente.nombre} ${cliente.apellido}</p>
                        <p class="text-sm text-gray-600">DNI: ${cliente.dni} | Tel: ${cliente.telefono}</p>
                    </div>
                `).join('');
                resultados.classList.remove('hidden');
                
                document.querySelectorAll('.cliente-item').forEach(item => {
                    item.addEventListener('click', function() {
                        const cliente = JSON.parse(this.dataset.cliente);
                        seleccionarCliente(cliente);
                    });
                });
            } else {
                resultados.innerHTML = `
                    <div class="p-4 text-center text-gray-500">
                        <p class="mb-2">❌ No se encontró ningún cliente</p>
                        <button type="button" id="btnMostrarRegistro" class="text-blue-600 hover:underline font-semibold">
                            <i class="fas fa-user-plus"></i> Registrar nuevo cliente
                        </button>
                    </div>
                `;
                resultados.classList.remove('hidden');
                
                document.getElementById('btnMostrarRegistro').addEventListener('click', mostrarFormularioRegistro);
            }
        } catch (error) {
            console.error('Error buscando cliente:', error);
        }
    }, 300);
});

// ========== SELECCIONAR CLIENTE ==========
function seleccion
arCliente(cliente) {
    clienteActual = cliente;
    
    document.getElementById('cliente_id').value = cliente.id;
    document.getElementById('clienteNombre').textContent = `${cliente.nombre} ${cliente.apellido}`;
    document.getElementById('clienteDni').textContent = `DNI: ${cliente.dni} | Tel: ${cliente.telefono}`;
    
    document.getElementById('clienteSeleccionado').classList.remove('hidden');
    document.getElementById('formularioReserva').classList.remove('hidden');
    document.getElementById('resultadosBusqueda').classList.add('hidden');
    document.getElementById('registroRapido').classList.add('hidden');
    document.getElementById('buscarCliente').disabled = true;
}

// ========== MOSTRAR FORMULARIO DE REGISTRO ==========
function mostrarFormularioRegistro() {
    const termino = document.getElementById('buscarCliente').value.trim();
    
    if (/^\d+$/.test(termino)) {
        document.getElementById('nuevo_dni').value = termino;
    } else {
        const partes = termino.split(' ');
        if (partes.length > 0) document.getElementById('nuevo_nombre').value = partes[0];
        if (partes.length > 1) document.getElementById('nuevo_apellido').value = partes.slice(1).join(' ');
    }
    
    document.getElementById('registroRapido').classList.remove('hidden');
    document.getElementById('resultadosBusqueda').classList.add('hidden');
}

// ========== CAMBIAR CLIENTE ==========
document.getElementById('cambiarCliente').addEventListener('click', function() {
    clienteActual = null;
    document.getElementById('cliente_id').value = '';
    document.getElementById('clienteSeleccionado').classList.add('hidden');
    document.getElementById('formularioReserva').classList.add('hidden');
    document.getElementById('buscarCliente').disabled = false;
    document.getElementById('buscarCliente').value = '';
    document.getElementById('buscarCliente').focus();
});

// ========== REGISTRAR CLIENTE RÁPIDO ==========
document.getElementById('btnRegistrarCliente').addEventListener('click', async function() {
    const datos = {
        dni: document.getElementById('nuevo_dni').value.trim(),
        nombre: document.getElementById('nuevo_nombre').value.trim(),
        apellido: document.getElementById('nuevo_apellido').value.trim(),
        telefono: document.getElementById('nuevo_telefono').value.trim(),
        email: document.getElementById('nuevo_email').value.trim(),
    };
    
    // Validaciones
    if (!datos.dni || !datos.nombre || !datos.apellido || !datos.telefono) {
        alert('❌ Por favor completa los campos obligatorios:\n- DNI\n- Nombre\n- Apellido\n- Teléfono');
        return;
    }
    
    if (datos.dni.length !== 8) {
        alert('❌ El DNI debe tener exactamente 8 dígitos');
        return;
    }
    
    if (datos.telefono.length !== 9) {
        alert('❌ El teléfono debe tener exactamente 9 dígitos');
        return;
    }
    
    if (!datos.telefono.startsWith('9')) {
        alert('❌ El teléfono debe empezar con 9');
        return;
    }
    
    const btn = document.getElementById('btnRegistrarCliente');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Registrando...';
    
    try {
        const response = await fetch('{{ route("api.crear-cliente-rapido") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(datos)
        });
        
        const result = await response.json();
        
        if (result.success) {
            seleccionarCliente(result.cliente);
            alert('✅ Cliente registrado exitosamente');
        } else {
            alert('❌ Error al registrar cliente');
        }
    } catch (error) {
        alert('❌ Error: ' + error.message);
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-user-plus mr-2"></i>Registrar y Continuar';
    }
});

// ========== VALIDACIÓN DE FECHAS EN TIEMPO REAL ==========
document.getElementById('fecha_evento')?.addEventListener('change', function() {
    const fechaEvento = new Date(this.value);
    const hoy = new Date();
    hoy.setHours(0, 0, 0, 0);
    
    if (fechaEvento < hoy) {
        alert('⚠️ La fecha del evento no puede ser en el pasado');
        this.value = '';
        return;
    }
    
    // Actualizar mínimo de fecha de devolución
    const minDevolucion = new Date(fechaEvento);
    minDevolucion.setDate(minDevolucion.getDate() + 1);
    document.getElementById('fecha_devolucion').min = minDevolucion.toISOString().split('T')[0];
    
    calcularTotal();
});

document.getElementById('fecha_devolucion')?.addEventListener('change', function() {
    const fechaEvento = document.getElementById('fecha_evento').value;
    const fechaDevolucion = this.value;
    
    if (fechaEvento && fechaDevolucion) {
        const evento = new Date(fechaEvento);
        const devolucion = new Date(fechaDevolucion);
        
        if (devolucion <= evento) {
            alert('⚠️ La fecha de devolución debe ser posterior a la fecha del evento');
            this.value = '';
            return;
        }
    }
    
    calcularTotal();
});

// ========== VALIDACIÓN DE ADELANTO EN TIEMPO REAL ==========
document.getElementById('adelanto')?.addEventListener('input', function(e) {
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
    
    // Validar que no supere el total
    const adelanto = parseFloat(value) || 0;
    if (montoTotalActual > 0 && adelanto > montoTotalActual) {
        document.getElementById('mensaje_adelanto').textContent = `⚠️ El adelanto no puede superar el total: S/ ${montoTotalActual.toFixed(2)}`;
        document.getElementById('mensaje_adelanto').classList.add('text-red-500');
    } else {
        document.getElementById('mensaje_adelanto').textContent = `Máximo: S/ ${montoTotalActual.toFixed(2)}`;
        document.getElementById('mensaje_adelanto').classList.remove('text-red-500');
        document.getElementById('mensaje_adelanto').classList.add('text-gray-500');
    }
    
    calcularTotal();
});

// ========== CALCULAR TOTAL ==========
function calcularTotal() {
    const ternoSelect = document.getElementById('terno_select');
    const fechaEvento = document.getElementById('fecha_evento').value;
    const fechaDevolucion = document.getElementById('fecha_devolucion').value;
    const adelanto = parseFloat(document.getElementById('adelanto').value) || 0;

    if (ternoSelect.selectedIndex > 0 && fechaEvento && fechaDevolucion) {
        const precioAlquiler = parseFloat(ternoSelect.options[ternoSelect.selectedIndex].dataset.precio);
        
        const fecha1 = new Date(fechaEvento);
        const fecha2 = new Date(fechaDevolucion);
        const diffTime = Math.abs(fecha2 - fecha1);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;

        const total = precioAlquiler * diffDays;
        const saldo = total - adelanto;
        
        montoTotalActual = total;

        document.getElementById('precio_dia').textContent = 'S/ ' + precioAlquiler.toFixed(2);
        document.getElementById('total_dias').textContent = diffDays;
        document.getElementById('monto_total').textContent = 'S/ ' + total.toFixed(2);
        document.getElementById('saldo').textContent = 'S/ ' + saldo.toFixed(2);
        
        // Actualizar mensaje de adelanto
        document.getElementById('mensaje_adelanto').textContent = `Máximo: S/ ${total.toFixed(2)}`;
    }
}

document.getElementById('terno_select').addEventListener('change', calcularTotal);

// ========== VALIDACIÓN FINAL ANTES DE ENVIAR ==========
document.getElementById('reservaForm').addEventListener('submit', function(e) {
    const cliente = document.getElementById('cliente_id').value;
    const terno = document.getElementById('terno_select').value;
    const fechaEvento = document.getElementById('fecha_evento').value;
    const fechaDevolucion = document.getElementById('fecha_devolucion').value;
    const adelanto = parseFloat(document.getElementById('adelanto').value) || 0;
    
    let errores = [];
    
    if (!cliente) {
        errores.push('Debe seleccionar o registrar un cliente');
    }
    
    if (!terno) {
        errores.push('Debe seleccionar un terno');
    }
    
    if (!fechaEvento) {
        errores.push('La fecha del evento es obligatoria');
    }
    
    if (!fechaDevolucion) {
        errores.push('La fecha de devolución es obligatoria');
    }
    
    if (fechaEvento && fechaDevolucion) {
        const evento = new Date(fechaEvento);
        const devolucion = new Date(fechaDevolucion);
        
        if (devolucion <= evento) {
            errores.push('La fecha de devolución debe ser posterior a la fecha del evento');
        }
    }
    
    if (adelanto < 0) {
        errores.push('El adelanto no puede ser negativo');
    }
    
    if (montoTotalActual > 0 && adelanto > montoTotalActual) {
        errores.push(`El adelanto (S/ ${adelanto.toFixed(2)}) no puede ser mayor que el total (S/ ${montoTotalActual.toFixed(2)})`);
    }
    
    if (errores.length > 0) {
        e.preventDefault();
        alert('❌ Errores encontrados:\n\n' + errores.join('\n'));
        return false;
    }
});
</script>
@endsection