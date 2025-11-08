@extends('layouts.app')

@section('title', 'Nuevo Usuario - TernoFit')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('usuarios.index') }}" class="text-purple-600 hover:underline">
            <i class="fas fa-arrow-left mr-2"></i>Volver a Usuarios
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">
            <i class="fas fa-user-plus text-purple-600"></i> Registrar Nuevo Usuario
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

        <form action="{{ route('usuarios.store') }}" method="POST" id="formUsuario">
            @csrf

            <div class="space-y-6">
                <!-- Nombre -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-user text-purple-600"></i> Nombre Completo *
                    </label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name"
                        value="{{ old('name') }}"
                        placeholder="Juan Pérez López"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 @error('name') @enderror"
                        required
                    >
                    <p class="text-xs text-gray-500 mt-1">Solo letras y espacios</p>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-envelope text-purple-600"></i> Email *
                    </label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email"
                        value="{{ old('email') }}"
                        placeholder="usuario@ternofit.com"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 @error('email') @enderror"
                        required
                    >
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Rol -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-user-tag text-purple-600"></i> Rol *
                    </label>
                    <select 
                        name="role" 
                        id="role"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 @error('role') @enderror"
                        required
                    >
                        <option value="">Seleccionar rol</option>
                        <option value="Administrador" {{ old('role') == 'Administrador' ? 'selected' : '' }}>
                            <i class="fas fa-crown"></i> Administrador (Acceso Total)
                        </option>
                        <option value="Empleado" {{ old('role') == 'Empleado' ? 'selected' : '' }}>
                            <i class="fas fa-user-tie"></i> Empleado (Acceso Limitado)
                        </option>
                    </select>
                    @error('role')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Contraseña -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-lock text-purple-600"></i> Contraseña *
                    </label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password"
                        placeholder="••••••••"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 @error('password') @enderror"
                        required
                    >
                    <p class="text-xs text-gray-500 mt-1">Mínimo 8 caracteres, debe incluir: 1 mayúscula, 1 minúscula y 1 número</p>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirmar Contraseña -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-lock text-purple-600"></i> Confirmar Contraseña *
                    </label>
                    <input 
                        type="password" 
                        name="password_confirmation" 
                        id="password_confirmation"
                        placeholder="••••••••"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"
                        required
                    >
                    <p class="text-xs text-gray-500 mt-1" id="mensaje_confirmacion">Las contraseñas deben coincidir</p>
                </div>
            </div>

            <div class="mt-8 flex space-x-4">
                <button 
                    type="submit" 
                    class="bg-purple-600 text-white px-8 py-3 rounded-lg hover:bg-purple-700 transition font-semibold">
                    <i class="fas fa-save mr-2"></i>Guardar Usuario
                </button>
                <a 
                    href="{{ route('usuarios.index') }}" 
                    class="bg-gray-500 text-white px-8 py-3 rounded-lg hover:bg-gray-600 transition font-semibold">
                    <i class="fas fa-times mr-2"></i>Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<script>
// ========== VALIDACIÓN EN TIEMPO REAL ==========

// Solo letras en Nombre
document.getElementById('name').addEventListener('input', function(e) {
    this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');
});

// Verificar coincidencia de contraseñas
document.getElementById('password_confirmation').addEventListener('input', verificarContraseñas);
document.getElementById('password').addEventListener('input', verificarContraseñas);

function verificarContraseñas() {
    const password = document.getElementById('password').value;
    const confirmation = document.getElementById('password_confirmation').value;
    const mensaje = document.getElementById('mensaje_confirmacion');
    
    if (confirmation === '') {
        mensaje.textContent = 'Las contraseñas deben coincidir';
        mensaje.classList.remove('text-green-500', 'text-red-500');
        mensaje.classList.add('text-gray-500');
    } else if (password === confirmation) {
        mensaje.textContent = '✓ Las contraseñas coinciden';
        mensaje.classList.remove('text-gray-500', 'text-red-500');
        mensaje.classList.add('text-green-500');
    } else {
        mensaje.textContent = '✗ Las contraseñas NO coinciden';
        mensaje.classList.remove('text-gray-500', 'text-green-500');
        mensaje.classList.add('text-red-500');
    }
}

// Validación antes de enviar
document.getElementById('formUsuario').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const confirmation = document.getElementById('password_confirmation').value;
    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const role = document.getElementById('role').value;
    
    let errores = [];
    
    if (name.trim() === '') {
        errores.push('El nombre es obligatorio');
    }
    
    if (email.trim() === '') {
        errores.push('El email es obligatorio');
    }
    
    if (!role) {
        errores.push('Debe seleccionar un rol');
    }
    
    if (password.length < 8) {
        errores.push('La contraseña debe tener al menos 8 caracteres');
    }
    
    if (!/[A-Z]/.test(password)) {
        errores.push('La contraseña debe contener al menos una letra mayúscula');
    }
    
    if (!/[a-z]/.test(password)) {
        errores.push('La contraseña debe contener al menos una letra minúscula');
    }
    
    if (!/\d/.test(password)) {
        errores.push('La contraseña debe contener al menos un número');
    }
    
    if (password !== confirmation) {
        errores.push('Las contraseñas no coinciden');
    }
    
    if (errores.length > 0) {
        e.preventDefault();
        alert('❌ Errores encontrados:\n\n' + errores.join('\n'));
        return false;
    }
});
</script>
@endsection