<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Terno;
use App\Models\Reserva;
use App\Models\Actividad;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function index(Request $request)
    {
        // Filtros de fecha
        $fechaInicio = $request->input('fecha_inicio', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $fechaFin = $request->input('fecha_fin', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // Estadísticas Generales
        $totalClientes = Cliente::count();
        $totalTernos = Terno::count();
        $ternosDisponibles = Terno::where('estado', 'Disponible')->count();
        $ternosAlquilados = Terno::where('estado', 'Alquilado')->count();

        // Reservas por período
        $reservasPeriodo = Reserva::whereBetween('fecha_reserva', [$fechaInicio, $fechaFin])->count();
        
        // Ingresos
        $ingresosTotales = Reserva::whereBetween('fecha_reserva', [$fechaInicio, $fechaFin])->sum('monto_total');
        $adelantosCobrados = Reserva::whereBetween('fecha_reserva', [$fechaInicio, $fechaFin])->sum('adelanto');
        $saldosPendientes = Reserva::whereBetween('fecha_reserva', [$fechaInicio, $fechaFin])->sum('saldo');

        // Reservas por estado
        $reservasPorEstado = Reserva::whereBetween('fecha_reserva', [$fechaInicio, $fechaFin])
            ->select('estado', DB::raw('count(*) as total'))
            ->groupBy('estado')
            ->get();

        // Ternos más alquilados
        $ternosMasAlquilados = Terno::withCount(['reservas' => function($query) use ($fechaInicio, $fechaFin) {
                $query->whereBetween('fecha_reserva', [$fechaInicio, $fechaFin]);
            }])
            ->orderBy('reservas_count', 'desc')
            ->take(10)
            ->get();

        // Categorías más solicitadas
        $categoriasMasSolicitadas = Terno::join('reservas', 'ternos.id', '=', 'reservas.terno_id')
            ->whereBetween('reservas.fecha_reserva', [$fechaInicio, $fechaFin])
            ->select('ternos.categoria', DB::raw('count(*) as total'))
            ->groupBy('ternos.categoria')
            ->orderBy('total', 'desc')
            ->get();

        // Clientes más frecuentes
        $clientesFrecuentes = Cliente::withCount(['reservas' => function($query) use ($fechaInicio, $fechaFin) {
                $query->whereBetween('fecha_reserva', [$fechaInicio, $fechaFin]);
            }])
            ->having('reservas_count', '>', 0)
            ->orderBy('reservas_count', 'desc')
            ->take(10)
            ->get();

        // Ingresos por mes (últimos 6 meses)
        $ingresosPorMes = Reserva::select(
                DB::raw('MONTH(fecha_reserva) as mes'),
                DB::raw('YEAR(fecha_reserva) as anio'),
                DB::raw('SUM(monto_total) as total')
            )
            ->where('fecha_reserva', '>=', Carbon::now()->subMonths(6))
            ->groupBy('anio', 'mes')
            ->orderBy('anio', 'asc')
            ->orderBy('mes', 'asc')
            ->get();

        // Reservas por día (últimos 30 días)
        $reservasPorDia = Reserva::select(
                DB::raw('DATE(fecha_reserva) as fecha'),
                DB::raw('count(*) as total')
            )
            ->where('fecha_reserva', '>=', Carbon::now()->subDays(30))
            ->groupBy('fecha')
            ->orderBy('fecha', 'asc')
            ->get();

        return view('reportes.index', compact(
            'fechaInicio',
            'fechaFin',
            'totalClientes',
            'totalTernos',
            'ternosDisponibles',
            'ternosAlquilados',
            'reservasPeriodo',
            'ingresosTotales',
            'adelantosCobrados',
            'saldosPendientes',
            'reservasPorEstado',
            'ternosMasAlquilados',
            'categoriasMasSolicitadas',
            'clientesFrecuentes',
            'ingresosPorMes',
            'reservasPorDia'
        ));
    }

    public function exportarExcel(Request $request)
    {
        // Implementar exportación a Excel (opcional)
        return response()->json(['message' => 'Función de exportación a Excel']);
    }

    public function exportarPDF(Request $request)
    {
        // Implementar exportación a PDF (opcional)
        return response()->json(['message' => 'Función de exportación a PDF']);
    }
}