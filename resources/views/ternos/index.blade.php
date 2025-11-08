@extends('layouts.app')

@section('title', 'Ternos - TernoFit')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                <i class="fas fa-vest text-purple-600"></i> Gestión de Ternos
            </h1>
            <p class="text-gray-600 mt-1">Administra tu inventario de ternos</p>
        </div>
        <a href="{{ route('ternos.create') }}" class="bg-purple-600 text-white px-6 py-3 rounded-lg hover:bg-purple-700 transition shadow-lg">
            <i class="fas fa-plus mr-2"></i>Nuevo Terno
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif

    <!-- Filtros -->
    <div class="bg-white rounded-xl shadow-lg p-4 mb-6">
        <form method="GET" action="{{ route('ternos.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <input type="text" name="buscar" value="{{ request('buscar') }}" 
                    placeholder="Buscar por código, marca o color..."
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500">
            </div>
            <div>
                <select name="categoria" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500">
                    <option value="">Todas las categorías</option>
                    <option value="Clásico" {{ request('categoria') == 'Clásico' ? 'selected' : '' }}>Clásico</option>
                    <option value="Moderno" {{ request('categoria') == 'Moderno' ? 'selected' : '' }}>Moderno</option>
                    <option value="Premium" {{ request('categoria') == 'Premium' ? 'selected' : '' }}>Premium</option>
                    <option value="Infantil" {{ request('categoria') == 'Infantil' ? 'selected' : '' }}>Infantil</option>
                </select>
            </div>
            <div>
                <select name="estado" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500">
                    <option value="">Todos los estados</option>
                    <option value="Disponible" {{ request('estado') == 'Disponible' ? 'selected' : '' }}>Disponible</option>
                    <option value="Alquilado" {{ request('estado') == 'Alquilado' ? 'selected' : '' }}>Alquilado</option>
                    <option value="Mantenimiento" {{ request('estado') == 'Mantenimiento' ? 'selected' : '' }}>Mantenimiento</option>
                </select>
            </div>
            <div class="flex space-x-2">
                <button type="submit" class="flex-1 bg-purple-600 text-white py-2 rounded-lg hover:bg-purple-700 transition">
                    <i class="fas fa-search"></i> Filtrar
                </button>
                <a href="{{ route('ternos.index') }}" class="flex-1 bg-gray-500 text-white py-2 rounded-lg hover:bg-gray-600 transition text-center">
                    <i class="fas fa-times"></i> Limpiar
                </a>
            </div>
        </form>
    </div>

    <!-- Grid de Ternos -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($ternos as $terno)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition">
                <div class="bg-gradient-to-br 
                    @if($terno->categoria == 'Premium') from-yellow-500
                    @elseif($terno->categoria == 'Moderno')
                    @elseif($terno->categoria == 'Infantil')
                    @else to-gray-800
                    @endif
                    p-6 text-white text-center">
                    <i class="fas fa-vest text-5xl mb-3"></i>
                    <h3 class="font-bold text-lg">{{ $terno->codigo }}</h3>
                    <p class="text-sm opacity-90">{{ $terno->categoria }}</p>
                </div>

                <div class="p-4">
                    <h4 class="font-semibold text-gray-800">{{ $terno->marca }}</h4>
                    <div class="mt-3 space-y-2 text-sm">
                        <p class="text-gray-600"><i class="fas fa-ruler text-blue-500"></i> Talla: {{ $terno->talla }}</p>
                        <p class="text-gray-600"><i class="fas fa-palette text-purple-500"></i> Color: {{ $terno->color }}</p>
                        <p class="text-green-600 font-bold text-lg"><i class="fas fa-tag"></i> S/ {{ number_format($terno->precio_alquiler, 2) }}</p>
                    </div>

                    <div class="mt-4">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            @if($terno->estado == 'Disponible') bg-green-100 text-green-800
                            @elseif($terno->estado == 'Alquilado')
                            @else
                            @endif">
                            {{ $terno->estado }}
                        </span>
                    </div>

                    <div class="mt-4 flex space-x-2">
                        <a href="{{ route('ternos.show', $terno) }}" class="flex-1 bg-green-500 text-white py-2 rounded-lg hover:bg-green-600 transition text-center text-sm">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('ternos.edit', $terno) }}" class="flex-1 bg-yellow-500 text-white py-2 rounded-lg hover:bg-yellow-600 transition text-center text-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('ternos.destroy', $terno) }}" method="POST" class="flex-1" onsubmit="return confirm('¿Eliminar este terno?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full bg-red-500 text-white py-2 rounded-lg hover:bg-red-600 transition text-sm">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-xl shadow-lg p-12 text-center">
                <i class="fas fa-vest text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500">No hay ternos registrados</p>
                <a href="{{ route('ternos.create') }}" class="text-purple-600 hover:underline mt-2 inline-block">Registrar el primero</a>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $ternos->links() }}
    </div>
</div>
@endsection