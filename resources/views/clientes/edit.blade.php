@extends('layouts.app')

@section('title', 'Editar Cliente - TernoFit')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('clientes.index') }}" class="text-blue-600 hover:underline">
            <i class="fas fa-arrow-left mr-2"></i>Volver a Clientes
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">
            <i class="fas fa-user-edit text-blue-600"></i> Editar Cliente
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

        <form action="{{ route('clientes.update', $cliente) }}" method="POST" id="formCliente">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- DNI -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-id-card text-blue-600"></i> DNI *
                    </label>
                    <input 
                        type="text" 
                        name="dni" 
                        id="dni"
                        value="{{ old('dni', $cliente->dni) }}"
                        maxlength="8"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('dni') @enderror"
                        required
                    >
                    <p class="text-xs text-gray-500 mt-1">Solo 8 dígitos numéricos</p>
                    @error('dni')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Teléfono -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-phone text-blue-600"></i> Teléfono *
                    </label>
                    <input 
                        type="text" 
                        name="telefono" 
                        id="telefono"
                        value="{{ old('telefono', $cliente->telefono) }}"
                        maxlength="9"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('telefono') @enderror"
                        required
                    >
                    <p class="text-xs text-gray-500 mt-1">9 dígitos, debe empezar con 9</p>
                    @error('telefono')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nombre -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-user text-blue-600"></i> Nombre *
                    </label>
                    <input 
                        type="text" 
                        name="nombre" 
                        id="nombre"
                        value="{{ old('nombre', $cliente->nombre) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('nombre') @enderror"
                        required
                    >
                    <p class="text-xs text-gray-500 mt-1">Solo letras y espacios</p>
                    @error('nombre')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Apellido -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-user text-blue-600"></i> Apellido *
                    </label>
                    <input 
                        type="text" 
                        name="apellido" 
                        id="apellido"
                        value="{{ old('apellido', $cliente->apellido) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('apellido') @enderror"
                        required
                    >
                    <p class="text-xs text-gray-500 mt-1">Solo letras y espacios</p>
                    @error('apellido')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-envelope text-blue-600"></i> Email (opcional)
                    </label>
                    <input 
                        type="email" 
                        name="email" 
                        value="{{ old('email', $cliente->email) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('email') @enderror"
                    >
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Dirección -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-map-marker-alt text-blue-600"></i> Dirección (opcional)
                    </label>
                    <textarea 
                        name="direccion" 
                        rows="3"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('direccion') @enderror"
                    >{{ old('direccion', $cliente->direccion) }}</textarea>
                    @error('direccion')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-8 flex space-x-4">
                <button 
                    type="submit" 
                    class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition font-semibold">
                    <i class="fas fa-save mr-2"></i>Actualizar Cliente
                </button>
                <a 
                    href="{{ route('clientes.index') }}" 
                    class="bg-gray-500 text-white px-8 py-3 rounded-lg hover:bg-gray-600 transition font-semibold">
                    <i class="fas fa-times mr-2"></i>Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<script>
// ========== VALIDACIÓN EN TIEMPO REAL ==========

document.getElementById('dni').addEventListener('input', function(e) {
    this.value = this.value.replace(/[^0-9]/g, '').slice(0, 8);
});

document.getElementById('telefono').addEventListener('input', function(e) {
    this.value = this.value.replace(/[^0-9]/g, '').slice(0, 9);
});

document.getElementById('nombre').addEventListener('input', function(e) {
    this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');
});

document.getElementById('apellido').addEventListener('input', function(e) {
    this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');
});

document.getElementById('formCliente').addEventListener('submit', function(e) {
    const dni = document.getElementById('dni').value;
    const telefono = document.getElementById('telefono').value;
    
    let errores = [];
    
    if (dni.length !== 8) {
        errores.push('El DNI debe tener exactamente 8 dígitos');
    }
    
    if (telefono.length !== 9) {
        errores.push('El teléfono debe tener exactamente 9 dígitos');
    }
    
    if (!telefono.startsWith('9')) {
        errores.push('El teléfono debe empezar con 9');
    }
    
    if (errores.length > 0) {
        e.preventDefault();
        alert('❌ Errores encontrados:\n\n' + errores.join('\n'));
        return false;
    }
});
</script>
@endsection