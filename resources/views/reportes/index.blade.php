@extends('layouts.app')

@section('title', 'Reportes - TernoFit')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-chart-bar text-orange-600"></i> Reportes y Estadísticas
        </h1>
        <p class="text-gray-600 mt-2">Análisis completo del negocio</p>
    </div>

    <!-- Filtros de Fecha -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <form method="GET" action="{{ route('reportes.index') }}" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-calendar-alt text-orange-600"></i> Fecha Inicio
                </label>
                <input type="date" name="fecha_inicio" value="{{ $fechaInicio }}" 
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-orange-500">
            </div>
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-calendar-alt text-orange-600"></i> Fecha Fin
                </label>
                <input type="date" name="fecha_fin" value="{{ $fechaFin }}" 
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-orange-500">
            </div>
            <div>
                <button type="submit" class="bg-orange-600 text-white px-6 py-2 rounded-lg hover:bg-orange-700 transition">
                    <i class="fas fa-filter mr-2"></i>Filtrar
                </button>
            </div>
            <div>
                <a href="{{ route('reportes.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition inline-block">
                    <i class="fas fa-redo mr-2"></i>Reiniciar
                </a>
            </div>
        </form>
    </div>

    <!-- Tarjetas de Resumen -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-80">Total Clientes</p>
                    <p class="text-3xl font-bold">{{ $totalClientes }}</p>
                </div>
                <i class="fas fa-users text-4xl opacity-50"></i>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-80">Total Ternos</p>
                    <p class="text-3xl font-bold">{{ $totalTernos }}</p>
                    <p class="text-xs mt-1">Disponibles: {{ $ternosDisponibles }}</p>
                </div>
                <i class="fas fa-vest text-4xl opacity-50"></i>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-80">Ingresos Período</p>
                    <p class="text-3xl font-bold">S/ {{ number_format($ingresosTotales, 2) }}</p>
                    <p class="text-xs mt-1">{{ $reservasPeriodo }} reservas</p>
                </div>
                <i class="fas fa-dollar-sign text-4xl opacity-50"></i>
            </div>
        </div>

        <div class="bg-gradient-to-br from-orange-500 to-orange-600 text-white rounded-xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-80">Saldo Pendiente</p>
                    <p class="text-3xl font-bold">S/ {{ number_format($saldosPendientes, 2) }}</p>
                    <p class="text-xs mt-1">Por cobrar</p>
                </div>
                <i class="fas fa-exclamation-circle text-4xl opacity-50"></i>
            </div>
        </div>
    </div>

    <!-- Gráficos Optimizados -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Reservas por Estado -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-chart-pie text-blue-600"></i> Reservas por Estado
            </h3>
            <div style="height: 280px;">
                <canvas id="reservasPorEstadoChart"></canvas>
            </div>
        </div>

        <!-- Categorías Más Solicitadas -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-chart-bar text-purple-600"></i> Categorías Más Solicitadas
            </h3>
            <div style="height: 280px;">
                <canvas id="categoriasChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Ingresos por Mes -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <h3 class="text-xl font-bold text-gray-800 mb-4">
            <i class="fas fa-chart-line text-green-600"></i> Ingresos por Mes (Últimos 6 meses)
        </h3>
        <div style="height: 300px;">
            <canvas id="ingresosPorMesChart"></canvas>
        </div>
    </div>

    <!-- Tablas de Datos -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Ternos Más Alquilados -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-trophy text-yellow-600"></i> Top 10 Ternos Más Alquilados
            </h3>
            <div class="space-y-3 max-h-96 overflow-y-auto">
                @forelse($ternosMasAlquilados as $index => $terno)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-yellow-400 to-orange-500 flex items-center justify-center text-white font-bold text-sm">
                                {{ $index + 1 }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">{{ $terno->codigo }}</p>
                                <p class="text-sm text-gray-600">{{ $terno->marca }} - {{ $terno->categoria }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-bold text-purple-600">{{ $terno->reservas_count }}</p>
                            <p class="text-xs text-gray-500">alquileres</p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">No hay datos disponibles</p>
                @endforelse
            </div>
        </div>

        <!-- Clientes Más Frecuentes -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-star text-blue-600"></i> Top 10 Clientes Frecuentes
            </h3>
            <div class="space-y-3 max-h-96 overflow-y-auto">
                @forelse($clientesFrecuentes as $index => $cliente)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center text-white font-bold text-sm">
                                {{ $index + 1 }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">{{ $cliente->nombre }} {{ $cliente->apellido }}</p>
                                <p class="text-sm text-gray-600">DNI: {{ $cliente->dni }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-bold text-green-600">{{ $cliente->reservas_count }}</p>
                            <p class="text-xs text-gray-500">reservas</p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">No hay datos disponibles</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Colores personalizados
const colors = {
    primary: '#3B82F6',
    success: '#10B981',
    warning: '#F59E0B',
    danger: '#EF4444',
    info: '#8B5CF6',
    purple: '#A855F7',
    pink: '#EC4899'
};

// Gráfico: Reservas por Estado
const reservasPorEstadoCtx = document.getElementById('reservasPorEstadoChart').getContext('2d');
new Chart(reservasPorEstadoCtx, {
    type: 'doughnut',
    data: {
        labels: [
            @foreach($reservasPorEstado as $reserva)
                '{{ $reserva->estado }}',
            @endforeach
        ],
        datasets: [{
            data: [
                @foreach($reservasPorEstado as $reserva)
                    {{ $reserva->total }},
                @endforeach
            ],
            backgroundColor: [
                colors.warning,
                colors.success,
                colors.info,
                colors.primary,
                colors.danger
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 15,
                    font: {
                        size: 12
                    }
                }
            }
        }
    }
});

// Gráfico: Categorías Más Solicitadas
const categoriasCtx = document.getElementById('categoriasChart').getContext('2d');
new Chart(categoriasCtx, {
    type: 'bar',
    data: {
        labels: [
            @foreach($categoriasMasSolicitadas as $cat)
                '{{ $cat->categoria }}',
            @endforeach
        ],
        datasets: [{
            label: 'Reservas',
            data: [
                @foreach($categoriasMasSolicitadas as $cat)
                    {{ $cat->total }},
                @endforeach
            ],
            backgroundColor: colors.purple,
            borderRadius: 8
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});

// Gráfico: Ingresos por Mes
const ingresosPorMesCtx = document.getElementById('ingresosPorMesChart').getContext('2d');
new Chart(ingresosPorMesCtx, {
    type: 'line',
    data: {
        labels: [
            @foreach($ingresosPorMes as $ingreso)
                '{{ ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"][$ingreso->mes - 1] }} {{ $ingreso->anio }}',
            @endforeach
        ],
        datasets: [{
            label: 'Ingresos (S/)',
            data: [
                @foreach($ingresosPorMes as $ingreso)
                    {{ $ingreso->total }},
                @endforeach
            ],
            borderColor: colors.success,
            backgroundColor: 'rgba(16, 185, 129, 0.1)',
            tension: 0.4,
            fill: true,
            borderWidth: 3,
            pointRadius: 5,
            pointBackgroundColor: colors.success
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: true,
                position: 'top'
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'S/ ' + value.toLocaleString();
                    }
                }
            }
        }
    }
});
</script>
@endsection