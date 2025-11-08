<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cliente;
use App\Models\Terno;
use App\Models\Reserva;
use App\Models\Actividad;
use Carbon\Carbon;

class DatosPruebaSeeder extends Seeder
{
    public function run(): void
    {
        echo "🚀 Iniciando generación de datos masivos...\n\n";

        // ==================== CLIENTES (100) ====================
        echo "👥 Creando 100 clientes...\n";
        
        $nombres = ['Juan', 'María', 'Carlos', 'Ana', 'Pedro', 'Lucía', 'Miguel', 'Carmen', 'José', 'Isabel', 
                    'Antonio', 'Rosa', 'Manuel', 'Teresa', 'Francisco', 'Laura', 'David', 'Patricia', 'Javier', 'Marta',
                    'Diego', 'Sandra', 'Alberto', 'Elena', 'Roberto', 'Cristina', 'Fernando', 'Beatriz', 'Ricardo', 'Silvia',
                    'Alejandro', 'Monica', 'Sergio', 'Andrea', 'Pablo', 'Natalia', 'Raúl', 'Adriana', 'Víctor', 'Daniela'];
        
        $apellidos = ['García', 'Rodríguez', 'Martínez', 'López', 'Pérez', 'González', 'Sánchez', 'Ramírez', 'Torres', 'Flores',
                      'Rivera', 'Gómez', 'Díaz', 'Cruz', 'Morales', 'Reyes', 'Gutiérrez', 'Ortiz', 'Jiménez', 'Hernández',
                      'Vargas', 'Castro', 'Ramos', 'Mendoza', 'Vega', 'Rojas', 'Delgado', 'Romero', 'Aguilar', 'Silva'];
        
        $clientes = [];
        for ($i = 1; $i <= 100; $i++) {
            $nombre = $nombres[array_rand($nombres)];
            $apellido = $apellidos[array_rand($apellidos)];
            $dni = str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT);
            
            $cliente = Cliente::create([
                'nombre' => $nombre,
                'apellido' => $apellido,
                'dni' => $dni,
                'telefono' => '9' . rand(10000000, 99999999),
                'email' => strtolower($nombre . '.' . $apellido . rand(1, 999) . '@example.com'),
                'direccion' => 'Av. ' . $apellidos[array_rand($apellidos)] . ' ' . rand(100, 999) . ', ' . ['Lima', 'Arequipa', 'Cusco', 'Trujillo', 'Chiclayo'][array_rand(['Lima', 'Arequipa', 'Cusco', 'Trujillo', 'Chiclayo'])],
                'created_at' => Carbon::now()->subDays(rand(1, 365)),
            ]);
            
            $clientes[] = $cliente;
        }
        echo "✅ 100 clientes creados\n\n";

        // ==================== TERNOS (80) ====================
        echo "🤵 Creando 80 ternos...\n";
        
        $marcas = ['Hugo Boss', 'Armani', 'Versace', 'Gucci', 'Dolce & Gabbana', 'Prada', 'Calvin Klein', 
                   'Tommy Hilfiger', 'Ralph Lauren', 'Zara', 'H&M', 'Mango', 'Dockers', 'Pierre Cardin',
                   'Adolfo Domínguez', 'Massimo Dutti', 'Brooks Brothers', 'Ermenegildo Zegna'];
        
        $colores = ['Negro', 'Azul Marino', 'Gris Oscuro', 'Gris Claro', 'Beige', 'Azul', 'Marrón', 
                    'Blanco', 'Vino', 'Verde Oscuro', 'Carbón', 'Plomo'];
        
        $tallas = ['S', 'M', 'L', 'XL', 'XXL'];
        $categorias = ['Clásico', 'Moderno', 'Premium', 'Infantil'];
        
        $descripciones = [
            'Ideal para bodas y eventos formales',
            'Diseño elegante y contemporáneo',
            'Corte slim fit moderno',
            'Estilo clásico atemporal',
            'Perfecto para ceremonias',
            'Tejido de alta calidad',
            'Diseño italiano exclusivo',
            'Confort y elegancia',
            'Para ocasiones especiales',
            'Acabado premium'
        ];
        
        $ternos = [];
        for ($i = 1; $i <= 80; $i++) {
            $categoria = $categorias[array_rand($categorias)];
            $precio = $categoria == 'Premium' ? rand(150, 250) : 
                     ($categoria == 'Moderno' ? rand(100, 150) : 
                     ($categoria == 'Infantil' ? rand(50, 80) : rand(80, 120)));
            
            $terno = Terno::create([
                'codigo' => 'TF-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'marca' => $marcas[array_rand($marcas)],
                'talla' => $tallas[array_rand($tallas)],
                'color' => $colores[array_rand($colores)],
                'categoria' => $categoria,
                'precio_alquiler' => $precio,
                'estado' => 'Disponible',
                'descripcion' => $descripciones[array_rand($descripciones)],
                'created_at' => Carbon::now()->subDays(rand(1, 180)),
            ]);
            
            $ternos[] = $terno;
        }
        echo "✅ 80 ternos creados\n\n";

        // ==================== RESERVAS (150) ====================
        echo "📅 Creando 150 reservas...\n";
        
        $estados = ['Pendiente', 'Confirmada', 'Entregada', 'Devuelta', 'Cancelada'];
        $observaciones = [
            'Boda familiar',
            'Graduación universitaria',
            'Evento corporativo',
            'Quinceañera',
            'Aniversario',
            'Cena de gala',
            'Ceremonia religiosa',
            'Fiesta de promoción',
            'Reunión empresarial',
            'Celebración especial',
            null
        ];
        
        $reservasCreadas = 0;
        $intentos = 0;
        $maxIntentos = 200;
        
        while ($reservasCreadas < 150 && $intentos < $maxIntentos) {
            $intentos++;
            
            $cliente = $clientes[array_rand($clientes)];
            $terno = $ternos[array_rand($ternos)];
            
            // Fechas aleatorias en los últimos 6 meses o próximos 3 meses
            $diasAtras = rand(-90, 180); // -90 (hace 3 meses) a +180 (dentro de 6 meses)
            $fechaReserva = Carbon::now()->addDays($diasAtras);
            $fechaEvento = Carbon::parse($fechaReserva)->addDays(rand(1, 15));
            $fechaDevolucion = Carbon::parse($fechaEvento)->addDays(rand(1, 3));
            
            // Calcular costos
            $dias = $fechaEvento->diffInDays($fechaDevolucion) + 1;
            $montoTotal = $terno->precio_alquiler * $dias;
            
            // Adelanto variable (entre 30% y 100%)
            $porcentajeAdelanto = rand(30, 100);
            $adelanto = ($montoTotal * $porcentajeAdelanto) / 100;
            $saldo = $montoTotal - $adelanto;
            
            // Determinar estado basado en la fecha
            $diasDesdeReserva = Carbon::now()->diffInDays($fechaReserva, false);
            $diasHastaEvento = Carbon::now()->diffInDays($fechaEvento, false);
            
            if ($diasHastaEvento < -3) {
                $estado = 'Devuelta';
            } elseif ($diasHastaEvento < 0) {
                $estado = 'Entregada';
            } elseif ($diasHastaEvento <= 7) {
                $estado = rand(0, 1) ? 'Confirmada' : 'Entregada';
            } else {
                $estado = ['Pendiente', 'Confirmada'][array_rand(['Pendiente', 'Confirmada'])];
            }
            
            // 10% de probabilidad de cancelación
            if (rand(1, 10) == 1) {
                $estado = 'Cancelada';
            }
            
            try {
                $reserva = Reserva::create([
                    'cliente_id' => $cliente->id,
                    'terno_id' => $terno->id,
                    'fecha_reserva' => $fechaReserva,
                    'fecha_evento' => $fechaEvento,
                    'fecha_devolucion' => $fechaDevolucion,
                    'monto_total' => $montoTotal,
                    'adelanto' => $adelanto,
                    'saldo' => $saldo,
                    'estado' => $estado,
                    'observaciones' => $observaciones[array_rand($observaciones)],
                    'created_at' => $fechaReserva,
                ]);
                
                // Actualizar estado del terno
                if (in_array($estado, ['Confirmada', 'Entregada'])) {
                    $terno->update(['estado' => 'Alquilado']);
                } elseif ($estado == 'Devuelta' || $estado == 'Cancelada') {
                    $terno->update(['estado' => 'Disponible']);
                }
                
                $reservasCreadas++;
                
                if ($reservasCreadas % 30 == 0) {
                    echo "   → $reservasCreadas reservas creadas...\n";
                }
                
            } catch (\Exception $e) {
                // Si hay error, continuar con el siguiente intento
                continue;
            }
        }
        
        echo "✅ $reservasCreadas reservas creadas\n\n";

        // ==================== ACTIVIDADES (200) ====================
        echo "📊 Creando 200 actividades...\n";
        
        $usuarios = \App\Models\User::all();
        $tiposActividad = [
            'Cliente Registrado',
            'Terno Agregado',
            'Reserva Creada',
            'Reserva Confirmada',
            'Reserva Entregada',
            'Reserva Devuelta',
            'Pago Recibido',
            'Cliente Actualizado',
            'Terno en Mantenimiento'
        ];
        
        for ($i = 1; $i <= 200; $i++) {
            $usuario = $usuarios->random();
            $tipo = $tiposActividad[array_rand($tiposActividad)];
            
            $descripcion = '';
            $valor = null;
            
            switch ($tipo) {
                case 'Cliente Registrado':
                    $clienteRandom = $clientes[array_rand($clientes)];
                    $descripcion = "Registró al cliente: {$clienteRandom->nombre} {$clienteRandom->apellido}";
                    break;
                    
                case 'Terno Agregado':
                    $ternoRandom = $ternos[array_rand($ternos)];
                    $descripcion = "Agregó el terno: {$ternoRandom->codigo} - {$ternoRandom->marca}";
                    break;
                    
                case 'Reserva Creada':
                    $reservaRandom = Reserva::inRandomOrder()->first();
                    if ($reservaRandom) {
                        $descripcion = "Creó reserva #{$reservaRandom->id} para {$reservaRandom->cliente->nombre} {$reservaRandom->cliente->apellido}";
                        $valor = $reservaRandom->monto_total;
                    }
                    break;
                    
                case 'Pago Recibido':
                    $reservaRandom = Reserva::inRandomOrder()->first();
                    if ($reservaRandom) {
                        $descripcion = "Recibió pago de reserva #{$reservaRandom->id}";
                        $valor = rand(50, 300);
                    }
                    break;
                    
                default:
                    $descripcion = "Realizó acción: {$tipo}";
                    break;
            }
            
            Actividad::create([
                'user_id' => $usuario->id,
                'tipo' => $tipo,
                'descripcion' => $descripcion,
                'valor' => $valor,
                'created_at' => Carbon::now()->subDays(rand(1, 90)),
            ]);
            
            if ($i % 50 == 0) {
                echo "   → $i actividades creadas...\n";
            }
        }
        
        echo "✅ 200 actividades creadas\n\n";

        // ==================== TAREAS (30) ====================
        echo "✅ Creando 30 tareas...\n";
        
        $admin = \App\Models\User::where('rol', 'admin')->first();
        $empleados = \App\Models\User::where('rol', 'empleado')->get();
        
        if ($empleados->count() == 0) {
            echo "⚠️  No hay empleados registrados. Creando un empleado...\n";
            $empleado = \App\Models\User::create([
                'name' => 'Empleado Demo',
                'email' => 'empleado2@ternofit.com',
                'password' => bcrypt('empleado123'),
                'rol' => 'empleado'
            ]);
            $empleados = collect([$empleado]);
        }
        
        $titulosTareas = [
            'Revisar inventario de ternos',
            'Confirmar reservas del día',
            'Llamar a clientes pendientes',
            'Verificar devoluciones',
            'Limpiar ternos devueltos',
            'Actualizar lista de precios',
            'Preparar ternos para eventos',
            'Hacer seguimiento a pagos',
            'Organizar bodega',
            'Revisar estado de ternos',
            'Contactar nuevos proveedores',
            'Actualizar catálogo en redes sociales',
            'Verificar reservas de la semana',
            'Preparar reporte mensual',
            'Revisar comentarios de clientes'
        ];
        
        $prioridades = ['baja', 'media', 'alta'];
        $estadosTarea = ['pendiente', 'en_proceso', 'completada'];
        
        for ($i = 1; $i <= 30; $i++) {
            $empleado = $empleados->random();
            $titulo = $titulosTareas[array_rand($titulosTareas)];
            
            \App\Models\Tarea::create([
                'asignado_por' => $admin->id,
                'asignado_a' => $empleado->id,
                'titulo' => $titulo . ' #' . $i,
                'descripcion' => 'Descripción detallada de la tarea ' . $i . '. Por favor completar antes de la fecha límite.',
                'prioridad' => $prioridades[array_rand($prioridades)],
                'estado' => $estadosTarea[array_rand($estadosTarea)],
                'fecha_vencimiento' => Carbon::now()->addDays(rand(-5, 15)),
                'created_at' => Carbon::now()->subDays(rand(1, 30)),
            ]);
        }
        
        echo "✅ 30 tareas creadas\n\n";

        // ==================== RESUMEN ====================
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "✨ RESUMEN DE DATOS GENERADOS\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        
        echo "👥 Clientes:          " . Cliente::count() . "\n";
        echo "🤵 Ternos:            " . Terno::count() . "\n";
        echo "   → Disponibles:     " . Terno::where('estado', 'Disponible')->count() . "\n";
        echo "   → Alquilados:      " . Terno::where('estado', 'Alquilado')->count() . "\n";
        echo "   → Mantenimiento:   " . Terno::where('estado', 'Mantenimiento')->count() . "\n";
        echo "\n";
        echo "📅 Reservas:          " . Reserva::count() . "\n";
        echo "   → Pendientes:      " . Reserva::where('estado', 'Pendiente')->count() . "\n";
        echo "   → Confirmadas:     " . Reserva::where('estado', 'Confirmada')->count() . "\n";
        echo "   → Entregadas:      " . Reserva::where('estado', 'Entregada')->count() . "\n";
        echo "   → Devueltas:       " . Reserva::where('estado', 'Devuelta')->count() . "\n";
        echo "   → Canceladas:      " . Reserva::where('estado', 'Cancelada')->count() . "\n";
        echo "\n";
        echo "📊 Actividades:       " . Actividad::count() . "\n";
        echo "✅ Tareas:            " . \App\Models\Tarea::count() . "\n";
        echo "\n";
        echo "💰 Ingresos Totales:  S/ " . number_format(Reserva::sum('monto_total'), 2) . "\n";
        echo "💵 Adelantos:         S/ " . number_format(Reserva::sum('adelanto'), 2) . "\n";
        echo "📊 Saldo Pendiente:   S/ " . number_format(Reserva::sum('saldo'), 2) . "\n";
        
        echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "🎉 ¡DATOS GENERADOS EXITOSAMENTE!\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    }
}