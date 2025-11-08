@extends('layouts.app')

@section('title', 'Nuevo Terno - TernoFit')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('ternos.index') }}" class="text-green-600 hover:underline">
            <i class="fas fa-arrow-left mr-2"></i>Volver a Ternos
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">
            <i class="fas fa-plus-circle text-green-600"></i> Registrar Nuevo Terno
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

        <form action="{{ route('ternos.store') }}" method="POST" id="formTerno">
            @csrf

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
                        value="{{ old('codigo') }}"
                        maxlength="50"
                        placeholder="TRN-001"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('codigo') @enderror"
                        required
                        style="text-transform: uppercase;"
                    >
                    <p class="text-xs text-gray-500 mt-1">Solo mayúsculas, números y guiones (ej: TRN-001)</p>
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
                        value="{{ old('marca') }}"
                        maxlength="255"
                        placeholder="Hugo Boss"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('marca') @enderror"
                        required
                    >
                    <p class="text-xs text-gray-500 mt-1">Letras, números y guiones permitidos</p>
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
                        <option value="Premium" {{ old('categoria') == 'Premium' ? 'selected' : '' }}>Premium</option>
                        <option value="Clásico" {{ old('categoria') == 'Clásico' ? 'selected' : '' }}>Clásico</option>
                        <option value="Moderno" {{ old('categoria') == 'Moderno' ? 'selected' : '' }}>Moderno</option>
                        <option value="Infantil" {{ old('categoria') == 'Infantil' ? 'selected' : '' }}>Infantil</option>
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
                        <option value="S" {{ old('talla') == 'S' ? 'selected' : '' }}>S - Small</option>
                        <option value="M" {{ old('talla') == 'M' ? 'selected' : '' }}>M - Medium</option>
                        <option value="L" {{ old('talla') == 'L' ? 'selected' : '' }}>L - Large</option>
                        <option value="XL" {{ old('talla') == 'XL' ? 'selected' : '' }}>XL - Extra Large</option>
                        <option value="XXL" {{ old('talla') == 'XXL' ? 'selected' : '' }}>XXL - Extra Extra Large</option>
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
                        value="{{ old('color') }}"
                        maxlength="100"
                        placeholder="Negro"
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
                        value="{{ old('precio_alquiler') }}"
                        step="0.01"
                        min="0"
                        max="9999.99"
                        placeholder="150.00"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('precio_alquiler') @enderror"
                        required
                    >
                    <p class="text-xs text-gray-500 mt-1">Máximo 2 decimales (ej: 150.50)</p>
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
                        <option value="Disponible" selected {{ old('estado') == 'Disponible' ? 'selected' : '' }}>Disponible</option>
                        <option value="Alquilado" {{ old('estado') == 'Alquilado' ? 'selected' : '' }}>Alquilado</option>
                        <option value="Mantenimiento" {{ old('estado') == 'Mantenimiento' ? 'selected' : '' }}>Mantenimiento</option>
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
                        placeholder="Detalles adicionales del terno..."
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('descripcion') @enderror"
                    >{{ old('descripcion') }}</textarea>
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
                    <i class="fas fa-save mr-2"></i>Guardar Terno
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
// ========== VALIDACIÓN EN TIEMPO REAL (Ternos) ==========

// Código: solo mayúsculas, números y guiones
document.getElementById('codigo').addEventListener('input', function(e) {
    this.value = this.value.toUpperCase().replace(/[^A-Z0-9\-]/g, '').slice(0, 50);
});

// Color: solo letras y espacios
document.getElementById('color').addEventListener('input', function(e) {
    this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');
});

// Precio: máximo 2 decimales
document.getElementById('precio_alquiler').addEventListener('input', function(e) {
    let value = this.value;
    
    // Permitir solo números y un punto decimal
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
});

// Validación antes de enviar
document.getElementById('formTerno').addEventListener('submit', function(e) {
    const codigo = document.getElementById('codigo').value;
    const precio = parseFloat(document.getElementById('precio_alquiler').value);
    const categoria = document.getElementById('categoria').value;
    const talla = document.getElementById('talla').value;
    
    let errores = [];
    
    if (codigo.trim() === '') {
        errores.push('El código es obligatorio');
    }
    
    if (!categoria) {
        errores.push('Debe seleccionar una categoría');
    }
    
    if (!talla) {
        errores.push('Debe seleccionar una talla');
    }
    
    if (isNaN(precio) || precio <= 0) {
        errores.push('El precio debe ser mayor a 0');
    }
    
    if (precio > 9999.99) {
        errores.push('El precio no puede superar S/ 9,999.99');
    }
    
    if (errores.length > 0) {
        e.preventDefault();
        alert('❌ Errores encontrados:\n\n' + errores.join('\n'));
        return false;
    }
});
</script>
@endsection