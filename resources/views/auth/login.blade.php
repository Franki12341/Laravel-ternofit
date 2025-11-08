<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - TernoFit</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-blue-600 via-purple-600 to-indigo-700 min-h-screen flex items-center justify-center p-4">
    
    <div class="w-full max-w-md">
        <!-- Logo y título -->
        <div class="text-center mb-8">
            <div class="inline-block bg-white rounded-full p-6 shadow-2xl mb-4">
                <i class="fas fa-user-tie text-6xl text-blue-600"></i>
            </div>
            <h1 class="text-4xl font-bold text-white mb-2">TernoFit</h1>
            <p class="text-blue-100">Sistema de Gestión de Alquiler de Ternos</p>
        </div>

        <!-- Formulario de Login -->
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Iniciar Sesión</h2>

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}">
                @csrf

                <!-- Email -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">
                        <i class="fas fa-envelope mr-2 text-blue-600"></i>Email
                    </label>
                    <input 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                        placeholder="ejemplo@ternofit.com"
                        required
                        autofocus
                    >
                </div>

                <!-- Password -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">
                        <i class="fas fa-lock mr-2 text-blue-600"></i>Contraseña
                    </label>
                    <input 
                        type="password" 
                        name="password" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                        placeholder="••••••••"
                        required
                    >
                </div>

                <!-- Recordarme -->
                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="ml-2 text-gray-700">Recordarme</span>
                    </label>
                </div>

                <!-- Botón Login -->
                <button 
                    type="submit" 
                    class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white font-bold py-3 rounded-lg hover:from-blue-700 hover:to-purple-700 transition duration-300 shadow-lg"
                >
                    <i class="fas fa-sign-in-alt mr-2"></i>Ingresar al Sistema
                </button>
            </form>

            <!-- Información de usuarios de prueba -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <p class="text-sm text-gray-600 text-center mb-3 font-semibold">Usuarios de Prueba:</p>
                <div class="bg-blue-50 rounded-lg p-3 mb-2">
                    <p class="text-sm"><strong>Admin:</strong> admin@ternofit.com</p>
                    <p class="text-sm text-gray-600">Contraseña: admin123</p>
                </div>
                <div class="bg-purple-50 rounded-lg p-3">
                    <p class="text-sm"><strong>Empleado:</strong> empleado@ternofit.com</p>
                    <p class="text-sm text-gray-600">Contraseña: empleado123</p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <p class="text-center text-white mt-6 text-sm">
            &copy; 2025 TernoFit - Todos los derechos reservados
        </p>
    </div>

</body>
</html>