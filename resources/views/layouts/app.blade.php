<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TernoFit - Alquiler de Ternos')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-gradient-to-r from-gray-900 to-gray-800 text-white shadow-lg">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-user-tie text-3xl"></i>
                    <a href="{{ route('dashboard') }}" class="text-2xl font-bold">TernoFit</a>
                </div>
                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ route('dashboard') }}" class="hover:text-blue-400 transition">
                        <i class="fas fa-home mr-1"></i>Inicio
                    </a>
                    <!-- Dentro del navbar, agregar: -->
                    <a href="{{ route('usuarios.index') }}" class="hover:text-blue-400 transition">
                        <i class="fas fa-users mr-1"></i>Usuarios
                    </a>
                    <a href="{{ route('clientes.index') }}" class="hover:text-blue-400 transition">
                        <i class="fas fa-users mr-1"></i>Clientes
                    </a>
                    <a href="{{ route('ia.index') }}" class="hover:text-blue-400 transition">
    <i class="fas fa-robot mr-1"></i>Asistente IA
</a>
                    <a href="{{ route('ternos.index') }}" class="hover:text-blue-400 transition">
                        <i class="fas fa-vest mr-1"></i>Ternos
                    </a>
                    <a href="{{ route('reservas.index') }}" class="hover:text-blue-400 transition">
                        <i class="fas fa-calendar-check mr-1"></i>Reservas
                    </a>
                    <a href="{{ route('reportes.index') }}" class="hover:text-blue-400 transition">
                        <i class="fas fa-chart-bar mr-1"></i>Reportes
                    </a>
                    
                    <!-- Menú de Usuario con Dropdown -->
                    <div class="relative border-l border-gray-700 pl-6">
                        <button id="userMenuButton" class="flex items-center space-x-3 hover:text-blue-400 transition focus:outline-none">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center font-bold text-lg">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <div class="text-left">
                                <p class="text-sm font-semibold">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-400">
                                    @if(Auth::user()->isAdmin())
                                        <i class="fas fa-crown text-yellow-400"></i> Administrador
                                    @else
                                        <i class="fas fa-user"></i> Empleado
                                    @endif
                                </p>
                            </div>
                            <i class="fas fa-chevron-down text-sm"></i>
                        </button>

                        <!-- Dropdown Menu -->
                        <div id="userDropdown" class="hidden absolute right-0 mt-3 w-72 bg-white rounded-xl shadow-2xl overflow-hidden z-50">
                            <!-- Header del dropdown -->
                            <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-4 text-white">
                                <div class="flex items-center space-x-3">
                                    <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center font-bold text-xl">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold">{{ Auth::user()->name }}</p>
                                        <p class="text-sm opacity-90">{{ Auth::user()->email }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Opciones del menú -->
                            <div class="py-2">
                                <a href="{{ route('perfil') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 transition">
                                    <i class="fas fa-user-circle w-5 text-blue-600"></i>
                                    <span class="ml-3">Mi Perfil</span>
                                </a>
                                
                                <a href="{{ route('mis-estadisticas') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 transition">
                                    <i class="fas fa-chart-line w-5 text-green-600"></i>
                                    <span class="ml-3">Mis Estadísticas</span>
                                </a>

                                @if(Auth::user()->isAdmin())
                                    <div class="border-t my-2"></div>
                                    <a href="{{ route('admin.empleados') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-purple-50 transition">
                                        <i class="fas fa-users-cog w-5 text-purple-600"></i>
                                        <span class="ml-3">Gestión de Empleados</span>
                                    </a>
                                    <a href="{{ route('admin.asignar-tareas') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-purple-50 transition">
                                        <i class="fas fa-tasks w-5 text-indigo-600"></i>
                                        <span class="ml-3">Asignar Tareas</span>
                                    </a>
                                @else
                                    <div class="border-t my-2"></div>
                                    <a href="{{ route('empleado.tareas') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-green-50 transition">
                                        <i class="fas fa-clipboard-list w-5 text-green-600"></i>
                                        <span class="ml-3">Mis Tareas</span>
                                    </a>
                                @endif

                                <div class="border-t my-2"></div>
                                
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center px-4 py-3 text-red-600 hover:bg-red-50 transition">
                                        <i class="fas fa-sign-out-alt w-5"></i>
                                        <span class="ml-3">Cerrar Sesión</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Contenido Principal -->
    <main class="container mx-auto px-6 py-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white mt-12 py-6">
        <div class="container mx-auto px-6 text-center">
            <p>&copy; 2025 TernoFit - Sistema de Alquiler de Ternos</p>
        </div>
    </footer>

    <!-- JavaScript para el dropdown -->
    <script>
        const userMenuButton = document.getElementById('userMenuButton');
        const userDropdown = document.getElementById('userDropdown');

        userMenuButton.addEventListener('click', () => {
            userDropdown.classList.toggle('hidden');
        });

        // Cerrar al hacer click fuera
        document.addEventListener('click', (e) => {
            if (!userMenuButton.contains(e.target) && !userDropdown.contains(e.target)) {
                userDropdown.classList.add('hidden');
            }
        });
    </script>
</body>
</html>