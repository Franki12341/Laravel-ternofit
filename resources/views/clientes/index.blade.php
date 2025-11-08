@extends('layouts.app')

@section('title', 'Clientes - TernoFit')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                <i class="fas fa-users text-blue-600"></i> Gestión de Clientes
            </h1>
            <p class="text-gray-600 mt-1">Administra tu cartera de clientes</p>
        </div>
        <a href="{{ route('clientes.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition shadow-lg">
            <i class="fas fa-plus mr-2"></i>Nuevo Cliente
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-blue-600 to-blue-700 text-white">
                    <tr>
                        <th class="px-6 py-4 text-left">DNI</th>
                        <th class="px-6 py-4 text-left">Nombre Completo</th>
                        <th class="px-6 py-4 text-left">Teléfono</th>
                        <th class="px-6 py-4 text-left">Email</th>
                        <th class="px-6 py-4 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($clientes as $cliente)
                        <tr class="hover:bg-blue-50 transition">
                            <td class="px-6 py-4 font-semibold text-gray-800">{{ $cliente->dni }}</td>
                            <td class="px-6 py-4">{{ $cliente->nombre }} {{ $cliente->apellido }}</td>
                            <td class="px-6 py-4">
                                <i class="fas fa-phone text-green-600"></i> {{ $cliente->telefono }}
                            </td>
                            <td class="px-6 py-4">
                                @if($cliente->email)
                                    <i class="fas fa-envelope text-blue-600"></i> {{ $cliente->email }}
                                @else
                                    <span class="text-gray-400">Sin email</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center space-x-2">
                                    <a href="{{ route('clientes.show', $cliente) }}" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 transition">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('clientes.edit', $cliente) }}" class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 transition">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('clientes.destroy', $cliente) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de eliminar este cliente?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-users text-6xl text-gray-300 mb-4"></i>
                                <p>No hay clientes registrados</p>
                                <a href="{{ route('clientes.create') }}" class="text-blue-600 hover:underline mt-2 inline-block">Registrar el primero</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $clientes->links() }}
    </div>
</div>
@endsection