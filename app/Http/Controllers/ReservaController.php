<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Cliente;
use App\Models\Terno;
use App\Models\Actividad;
use Illuminate\Http\Request;

class ReservaController extends Controller
{
    public function index(Request $request)
{
    $query = Reserva::with(['cliente', 'terno']);

    // Filtro por estado
    if ($request->filled('estado')) {
        $query->where('estado', $request->estado);
    }

    // NUEVO: Filtro por método de pago
    if ($request->filled('metodo_pago')) {
        $query->where('metodo_pago', $request->metodo_pago);
    }

    $reservas = $query->orderBy('created_at', 'desc')->paginate(10);
    return view('reservas.index', compact('reservas'));
}

    public function create()
    {
        $clientes = Cliente::orderBy('nombre')->get();
        $ternos = Terno::where('estado', 'Disponible')->orderBy('codigo')->get();
        return view('reservas.create', compact('clientes', 'ternos'));
    }

    // ========== BÚSQUEDA DE CLIENTE ==========
    public function buscarCliente(Request $request)
    {
        $termino = $request->input('termino');
        
        $clientes = Cliente::where('dni', 'LIKE', "%{$termino}%")
            ->orWhere('nombre', 'LIKE', "%{$termino}%")
            ->orWhere('apellido', 'LIKE', "%{$termino}%")
            ->orWhereRaw("CONCAT(nombre, ' ', apellido) LIKE ?", ["%{$termino}%"])
            ->limit(10)
            ->get();
        
        return response()->json($clientes);
    }

    // ========== CREAR CLIENTE RÁPIDO ==========
    public function crearClienteRapido(Request $request)
    {
        $request->validate([
            'dni' => 'required|string|max:8|unique:clientes',
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'telefono' => 'required|string|max:15',
            'email' => 'nullable|email|max:255',
        ]);

        $cliente = Cliente::create([
            'dni' => $request->dni,
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'telefono' => $request->telefono,
            'email' => $request->email,
            'direccion' => null,
        ]);

        Actividad::create([
            'user_id' => auth()->id(),
            'tipo' => 'Cliente Registrado (Rápido)',
            'descripcion' => "Registró cliente desde reserva: {$cliente->nombre} {$cliente->apellido}",
            'valor' => null
        ]);

        return response()->json([
            'success' => true,
            'cliente' => $cliente
        ]);
    }

    public function store(Request $request)
{
    // ========== VALIDACIONES MEJORADAS ==========
    $request->validate([
        'cliente_id' => [
            'required',
            'exists:clientes,id'
        ],
        'terno_id' => [
            'required',
            'exists:ternos,id'
        ],
        'fecha_reserva' => [
            'required',
            'date',
            'before_or_equal:today'
        ],
        'fecha_evento' => [
            'required',
            'date',
            'after_or_equal:today',
            'after_or_equal:fecha_reserva'
        ],
        'fecha_devolucion' => [
            'required',
            'date',
            'after:fecha_evento'
        ],
        'adelanto' => [
            'required',
            'numeric',
            'min:0',
            'regex:/^\d+(\.\d{1,2})?$/'
        ],
        'metodo_pago' => [  // ⬅️ NUEVO
            'required',
            'in:Efectivo,Yape,Plin,Transferencia,Tarjeta'
        ],
        'observaciones' => [
            'nullable',
            'string',
            'max:1000'
        ],
    ], [
        // Mensajes personalizados
        'cliente_id.required' => 'Debe seleccionar un cliente',
        'cliente_id.exists' => 'El cliente seleccionado no existe',
        
        'terno_id.required' => 'Debe seleccionar un terno',
        'terno_id.exists' => 'El terno seleccionado no existe',
        
        'fecha_reserva.required' => 'La fecha de reserva es obligatoria',
        'fecha_reserva.date' => 'La fecha de reserva no es válida',
        'fecha_reserva.before_or_equal' => 'La fecha de reserva no puede ser futura',
        
        'fecha_evento.required' => 'La fecha del evento es obligatoria',
        'fecha_evento.date' => 'La fecha del evento no es válida',
        'fecha_evento.after_or_equal' => 'La fecha del evento no puede ser anterior a hoy o a la fecha de reserva',
        
        'fecha_devolucion.required' => 'La fecha de devolución es obligatoria',
        'fecha_devolucion.date' => 'La fecha de devolución no es válida',
        'fecha_devolucion.after' => 'La fecha de devolución debe ser posterior a la fecha del evento',
        
        'adelanto.required' => 'El adelanto es obligatorio',
        'adelanto.numeric' => 'El adelanto debe ser un número válido',
        'adelanto.min' => 'El adelanto no puede ser negativo',
        'adelanto.regex' => 'El adelanto puede tener máximo 2 decimales',
        
        'metodo_pago.required' => 'Debe seleccionar un método de pago',  // ⬅️ NUEVO
        'metodo_pago.in' => 'El método de pago debe ser: Efectivo, Yape, Plin, Transferencia o Tarjeta',
        
        'observaciones.max' => 'Las observaciones no pueden superar los 1000 caracteres',
    ]);

    // Verificar que el terno esté disponible
    $terno = Terno::findOrFail($request->terno_id);
    if ($terno->estado !== 'Disponible') {
        return back()->withErrors(['terno_id' => 'El terno seleccionado no está disponible'])->withInput();
    }

    // Calcular monto total
    $dias = \Carbon\Carbon::parse($request->fecha_evento)->diffInDays($request->fecha_devolucion) + 1;
    $monto_total = $terno->precio_alquiler * $dias;
    $saldo = $monto_total - $request->adelanto;

    // Validación adicional
    if ($request->adelanto > $monto_total) {
        return back()->withErrors(['adelanto' => 'El adelanto (S/ ' . number_format($request->adelanto, 2) . ') no puede ser mayor que el monto total (S/ ' . number_format($monto_total, 2) . ')'])->withInput();
    }

    $reserva = Reserva::create([
        'cliente_id' => $request->cliente_id,
        'terno_id' => $request->terno_id,
        'fecha_reserva' => $request->fecha_reserva,
        'fecha_evento' => $request->fecha_evento,
        'fecha_devolucion' => $request->fecha_devolucion,
        'monto_total' => $monto_total,
        'adelanto' => $request->adelanto,
        'metodo_pago' => $request->metodo_pago,  // ⬅️ NUEVO
        'saldo' => $saldo,
        'observaciones' => $request->observaciones,
    ]);

    // Actualizar estado del terno
    $terno->update(['estado' => 'Alquilado']);

    // Registrar actividad
    Actividad::create([
        'user_id' => auth()->id(),
        'tipo' => 'Reserva Creada',
        'descripcion' => "Creó reserva #{$reserva->id} para {$reserva->cliente->nombre} {$reserva->cliente->apellido} - Pago: {$request->metodo_pago}",  // ⬅️ ACTUALIZADO
        'valor' => $monto_total
    ]);

    return redirect()->route('reservas.index')->with('success', 'Reserva creada exitosamente');
}

    public function edit(Reserva $reserva)
    {
        $clientes = Cliente::orderBy('nombre')->get();
        $ternos = Terno::orderBy('codigo')->get();
        return view('reservas.edit', compact('reserva', 'clientes', 'ternos'));
    }

    public function update(Request $request, Reserva $reserva)
{
    // ========== VALIDACIONES MEJORADAS ==========
    $request->validate([
        'estado' => [
            'required',
            'in:Pendiente,Confirmada,Entregada,Devuelta,Cancelada'
        ],
        'adelanto' => [
            'required',
            'numeric',
            'min:0',
            'max:' . $reserva->monto_total,
            'regex:/^\d+(\.\d{1,2})?$/'
        ],
        'metodo_pago' => [  // ⬅️ NUEVO
            'required',
            'in:Efectivo,Yape,Plin,Transferencia,Tarjeta'
        ],
        'observaciones' => [
            'nullable',
            'string',
            'max:1000'
        ],
    ], [
        'estado.required' => 'El estado es obligatorio',
        'estado.in' => 'El estado debe ser: Pendiente, Confirmada, Entregada, Devuelta o Cancelada',
        
        'adelanto.required' => 'El adelanto es obligatorio',
        'adelanto.numeric' => 'El adelanto debe ser un número válido',
        'adelanto.min' => 'El adelanto no puede ser negativo',
        'adelanto.max' => 'El adelanto no puede ser mayor que el monto total (S/ ' . number_format($reserva->monto_total, 2) . ')',
        'adelanto.regex' => 'El adelanto puede tener máximo 2 decimales',
        
        'metodo_pago.required' => 'Debe seleccionar un método de pago',  // ⬅️ NUEVO
        'metodo_pago.in' => 'El método de pago debe ser: Efectivo, Yape, Plin, Transferencia o Tarjeta',
    ]);

    $saldo = $reserva->monto_total - $request->adelanto;

    $reserva->update([
        'estado' => $request->estado,
        'adelanto' => $request->adelanto,
        'metodo_pago' => $request->metodo_pago,  // ⬅️ NUEVO
        'saldo' => $saldo,
        'observaciones' => $request->observaciones,
    ]);

    // Si se devuelve o cancela, actualizar estado del terno
    if ($request->estado === 'Devuelta' || $request->estado === 'Cancelada') {
        $reserva->terno->update(['estado' => 'Disponible']);
    }

    return redirect()->route('reservas.index')->with('success', 'Reserva actualizada exitosamente');
}

    public function show(Reserva $reserva)
    {
        return view('reservas.show', compact('reserva'));
    }
}