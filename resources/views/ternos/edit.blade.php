@extends('layouts.app')

@section('title', 'Editar Terno - TernoFit')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('ternos.index') }}" class="text-green-600 hover:underline">
            <i class="fas fa-arrow-left mr-2"></i>Volver a Ternos
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">
            <i class="fas fa-edit text-green-600"></i> Editar Terno
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

        <form action="{{ route('ternos.update', $terno) }}" method="POST" id="formTerno">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Código -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-barcode text-green-600"></i> Código *
                    </label>
                    <input 
                        type="text" 
                        name="codigo" 
                        id="codigo"
                        value="{{ old('codigo', $terno->codigo) }}"
                        maxlength="50"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('codigo') @enderror"
                        required
                        style="text-transform: uppercase;"
                    >
                    <p class="text-xs text-gray-500 mt-1">Solo mayúsculas, números y guiones</p>
                    @error('codigo')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Marca -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-tag text-green-600"></i> Marca *
                    </label>
                    <input 
                        type="text" 
                        name="marca" 
                        id="marca"
                        value="{{ old('marca', $terno->marca) }}"
                        maxlength="255"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('marca') @enderror"
                        required
                    >
                    @error('marca')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Categoría -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-layer-group text-green-600"></i> Categoría *
                    </label>
                    <select 
                        name="categoria" 
                        id="categoria"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('categoria') @enderror"
                        required
                    >
                        <option value="">Seleccionar categoría</option>
                        <option value="Premium" {{ old('categoria', $terno->categoria) == 'Premium' ? 'selected' : '' }}>Premium</option>
                        <option value="Clásico" {{ old('categoria', $terno->categoria) == 'Clásico' ? 'selected' : '' }}>Clásico</option>
                        <option value="Moderno" {{ old('categoria', $terno->categoria) == 'Moderno' ? 'selected' : '' }}>Moderno</option>
                        <option value="Infantil" {{ old('categoria', $terno->categoria) == 'Infantil' ? 'selected' : '' }}>Infantil</option>
                    </select>
                    @error('categoria')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Talla -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-ruler text-green-600"></i> Talla *
                    </label>
                    <select 
                        name="talla" 
                        id="talla"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('talla') @enderror"
                        required
                    >
                        <option value="">Seleccionar talla</option>
                        <option value="S" {{ old('talla', $terno->talla) == 'S' ? 'selected' : '' }}>S - Small</option>
                        <option value="M" {{ old('talla', $terno->talla) == 'M' ? 'selected' : '' }}>M - Medium</option>
                        <option value="L" {{ old('talla', $terno->talla) == 'L' ? 'selected' : '' }}>L - Large</option>
                        <option value="XL" {{ old('talla', $terno->talla) == 'XL' ? 'selected' : '' }}>XL - Extra Large</option>
                        <option value="XXL" {{ old('talla', $terno->talla) == 'XXL' ? 'selected' : '' }}>XXL - Extra Extra Large</option>
                    </select>
                    @error('talla')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Color -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-palette text-green-600"></i> Color *
                    </label>
                    <input 
                        type="text" 
                        name="color" 
                        id="color"
                        value="{{ old('color', $terno->color) }}"
                        maxlength="100"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('color') @enderror"
                        required
                    >
                    <p class="text-xs text-gray-500 mt-1">Solo letras y espacios</p>
                    @error('color')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Precio de Alquiler -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-dollar-sign text-green-600"></i> Precio de Alquiler (S/) *
                    </label>
                    <input 
                        type="number" 
                        name="precio_alquiler" 
                        id="precio_alquiler"
                        value="{{ old('precio_alquiler', $terno->precio_alquiler) }}"
                        step="0.01"
                        min="0"
                        max="9999.99"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('precio_alquiler') @enderror"
                        required
                    >
                    <p class="text-xs text-gray-500 mt-1">Máximo 2 decimales</p>
                    @error('precio_alquiler')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

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
                        <option value="">Seleccionar estado</option>
                        <option value="Disponible" {{ old('estado', $terno->estado) == 'Disponible' ? 'selected' : '' }}>Disponible</option>
                        <option value="Alquilado" {{ old('estado', $terno->estado) == 'Alquilado' ? 'selected' : '' }}>Alquilado</option>
                        <option value="Mantenimiento" {{ old('estado', $terno->estado) == 'Mantenimiento' ? 'selected' : '' }}>Mantenimiento</option>
                    </select>
                    @error('estado')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Descripción -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-align-left text-green-600"></i> Descripción (opcional)
                    </label>
                    <textarea 
                        name="descripcion" 
                        id="descripcion"
                        rows="4"
                        maxlength="1000"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('descripcion') @enderror"
                    >{{ old('descripcion', $terno->descripcion) }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Máximo 1000 caracteres</p>
                    @error('descripcion')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-8 flex space-x-4">
                <button 
                    type="submit" 
                    class="bg-green-600 text-white px-8 py-3 rounded-lg hover:bg-green-700 transition font-semibold">
                    <i class="fas fa-save mr-2"></i>Actualizar Terno
                </button>
                <a 
                    href="{{ route('ternos.index') }}" 
                    class="bg-gray-500 text-white px-8 py-3 rounded-lg hover:bg-gray-600 transition font-semibold">
                    <i class="fas fa-times mr-2"></i>Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<script>
// ========== VALIDACIÓN EN TIEMPO REAL ==========

document.getElementById('codigo').addEventListener('input', function(e) {
    this.value = this.value.toUpperCase().replace(/[^A-Z0-9\-]/g, '').slice(0, 50);
});

document.getElementById('color').addEventListener('input', function(e) {
    this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');
});

document.getElementById('precio_alquiler').addEventListener('input', function(e) {
    let value = this.value;
    value = value.replace(/[^\d.]/g, '');
    const parts = value.split('.');
    if (parts.length > 2) {
        value = parts[0] + '.' + parts.slice(1).join('');
    }
    if (parts[1] && parts[1].length > 2) {
        value = parts[0] + '.' + parts[1].substring(0, 2);
    }
    this.value = value;
});

document.getElementById('formTerno').addEventListener('submit', function(e) {
    const precio = parseFloat(document.getElementById('precio_alquiler').value);
    
    if (isNaN(precio) || precio <= 0) {
        e.preventDefault();
        alert('❌ El precio debe ser mayor a 0');
        return false;
    }
    
    if (precio > 9999.99) {
        e.preventDefault();
        alert('❌ El precio no puede superar S/ 9,999.99');
        return false;
    }
});
</script>
@endsection