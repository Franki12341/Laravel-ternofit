<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Terno;
use OpenAI;
use Illuminate\Support\Facades\Log;

class IAController extends Controller
{
    private $useOpenAI = true; // Cambiar a false para usar sistema local
    
    public function index()
    {
        return view('ia.index');
    }

    public function chat(Request $request)
    {
        $mensaje = $request->input('mensaje');
        
        // Intentar con OpenAI primero
        if ($this->useOpenAI) {
            try {
                return $this->chatConOpenAI($mensaje);
            } catch (\Exception $e) {
                // Si falla OpenAI, usar sistema local
                Log::warning('OpenAI falló, usando respuestas locales: ' . $e->getMessage());
                return $this->chatLocal($mensaje);
            }
        }
        
        // Usar sistema local directamente
        return $this->chatLocal($mensaje);
    }
    
    private function chatConOpenAI($mensaje)
    {
        $totalTernos = Terno::count();
        $ternosDisponibles = Terno::where('estado', 'Disponible')->count();
        $categorias = Terno::distinct('categoria')->pluck('categoria')->implode(', ');
        $precioMin = Terno::min('precio_alquiler') ?? 0;
        $precioMax = Terno::max('precio_alquiler') ?? 0;

        $contexto = "Eres un asistente virtual amigable de TernoFit. Tenemos {$totalTernos} ternos, {$ternosDisponibles} disponibles. Categorías: {$categorias}. Precios: S/ {$precioMin} - S/ {$precioMax}. Responde en español, breve y amigable.";
        
        $client = OpenAI::client(config('services.openai.key'));

        $response = $client->chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'system', 'content' => $contexto],
                ['role' => 'user', 'content' => $mensaje]
            ],
            'max_tokens' => 250,
            'temperature' => 0.7,
        ]);

        return response()->json([
            'success' => true,
            'respuesta' => $response->choices[0]->message->content
        ]);
    }
    
    private function chatLocal($mensaje)
    {
        $mensajeLower = strtolower($mensaje);
        
        $totalTernos = Terno::count();
        $ternosDisponibles = Terno::where('estado', 'Disponible')->count();
        $categorias = Terno::distinct('categoria')->pluck('categoria')->implode(', ');
        $precioMin = Terno::min('precio_alquiler') ?? 0;
        $precioMax = Terno::max('precio_alquiler') ?? 0;
        
        // Respuestas inteligentes locales
        if (preg_match('/\b(hola|buenos|buenas|saludos|hey)\b/', $mensajeLower)) {
            $respuesta = "¡Hola! 👋 Bienvenido a TernoFit. Soy tu asistente virtual. Puedo ayudarte con información sobre nuestros ternos, precios, disponibilidad y reservas. ¿En qué puedo ayudarte hoy?";
        }
        elseif (preg_match('/\b(precio|costo|cuánto|cuanto|vale)\b/', $mensajeLower)) {
            $respuesta = "💰 Nuestros precios de alquiler van desde S/ {$precioMin} hasta S/ {$precioMax} por día, dependiendo de la categoría y modelo del terno. ¿Te gustaría saber sobre alguna categoría específica?";
        }
        elseif (preg_match('/\b(disponible|hay|tienen|cuántos|cuantos)\b/', $mensajeLower)) {
            $respuesta = "🤵 Actualmente tenemos {$ternosDisponibles} ternos disponibles de un total de {$totalTernos} en nuestro inventario. Todos están listos para ser alquilados en las fechas que necesites.";
        }
        elseif (preg_match('/\b(categoría|categoria|tipo|tipos|modelo|modelos)\b/', $mensajeLower)) {
            $respuesta = "✨ Nuestras categorías disponibles son: {$categorias}. Cada categoría tiene características y precios diferentes. ¿Cuál te interesa?";
        }
        elseif (preg_match('/\b(reserva|alquilar|rentar|apartar)\b/', $mensajeLower)) {
            $respuesta = "📅 Para hacer una reserva es muy fácil: ve al menú 'Reservas', selecciona el terno que te guste, indica la fecha del evento y la fecha de devolución. ¡Te guiaremos en todo el proceso!";
        }
        elseif (preg_match('/\b(talla|tallas|medida|tamaño)\b/', $mensajeLower)) {
            $respuesta = "📏 Tenemos ternos en todas las tallas: S (Small), M (Medium), L (Large), XL (Extra Large) y XXL. ¿Cuál es tu talla?";
        }
        elseif (preg_match('/\b(premium|clásico|clasico|moderno|infantil)\b/', $mensajeLower)) {
            $premium = Terno::where('categoria', 'Premium')->where('estado', 'Disponible')->count();
            $clasico = Terno::where('categoria', 'Clásico')->where('estado', 'Disponible')->count();
            $moderno = Terno::where('categoria', 'Moderno')->where('estado', 'Disponible')->count();
            $infantil = Terno::where('categoria', 'Infantil')->where('estado', 'Disponible')->count();
            
            $respuesta = "🎩 Disponibilidad por categoría:\n\n";
            $respuesta .= "• Premium: {$premium} ternos\n";
            $respuesta .= "• Clásico: {$clasico} ternos\n";
            $respuesta .= "• Moderno: {$moderno} ternos\n";
            $respuesta .= "• Infantil: {$infantil} ternos\n\n";
            $respuesta .= "¿Cuál categoría te interesa más?";
        }
        elseif (preg_match('/\b(gracias|agradezco|ok|vale|perfecto)\b/', $mensajeLower)) {
            $respuesta = "😊 ¡Con gusto! Si necesitas más ayuda o quieres hacer una reserva, estoy aquí para ayudarte. ¡Que tengas un excelente día!";
        }
        else {
            $respuesta = "🤔 Puedo ayudarte con:\n\n• Información sobre precios y disponibilidad\n• Categorías de ternos\n• Cómo hacer una reserva\n• Tallas disponibles\n• Y cualquier otra consulta sobre TernoFit\n\n¿Qué te gustaría saber?";
        }
        
        return response()->json([
            'success' => true,
            'respuesta' => $respuesta
        ]);
    }

    public function recomendarTerno(Request $request)
    {
        $ocasion = $request->input('ocasion', 'evento formal');
        $talla = $request->input('talla');
        $presupuesto = $request->input('presupuesto', 1000);

        $ternos = Terno::where('estado', 'Disponible')
            ->when($talla, function($query, $talla) {
                return $query->where('talla', $talla);
            })
            ->when($presupuesto, function($query, $presupuesto) {
                return $query->where('precio_alquiler', '<=', $presupuesto);
            })
            ->get();

        if ($ternos->isEmpty()) {
            return response()->json([
                'success' => false,
                'error' => 'No hay ternos disponibles con esos criterios. Intenta ajustar tu presupuesto o quitar el filtro de talla.'
            ]);
        }

        if ($this->useOpenAI) {
            try {
                return $this->recomendarConOpenAI($ocasion, $talla, $presupuesto, $ternos);
            } catch (\Exception $e) {
                return $this->recomendarLocal($ocasion, $ternos);
            }
        }
        
        return $this->recomendarLocal($ocasion, $ternos);
    }
    
    private function recomendarConOpenAI($ocasion, $talla, $presupuesto, $ternos)
    {
        $ternosInfo = $ternos->take(5)->map(function($terno) {
            return "{$terno->codigo} - {$terno->marca} ({$terno->categoria}, {$terno->talla}) S/{$terno->precio_alquiler}";
        })->implode(' | ');

        $client = OpenAI::client(config('services.openai.key'));

        $response = $client->chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => "Eres asesor de moda. Recomienda brevemente UN terno específico."
                ],
                [
                    'role' => 'user',
                    'content' => "Ocasión: {$ocasion}. Presupuesto: S/{$presupuesto}. Ternos: {$ternosInfo}. Recomienda UNO mencionando su código."
                ]
            ],
            'max_tokens' => 150,
            'temperature' => 0.8,
        ]);

        return response()->json([
            'success' => true,
            'recomendacion' => $response->choices[0]->message->content,
            'ternos' => $ternos
        ]);
    }
    
    private function recomendarLocal($ocasion, $ternos)
    {
        $ternoRecomendado = $ternos->sortByDesc('precio_alquiler')->first();
        
        $recomendacion = "🎩 Para tu {$ocasion}, te recomiendo el terno {$ternoRecomendado->codigo} - {$ternoRecomendado->marca}.\n\n";
        $recomendacion .= "✨ Es de categoría {$ternoRecomendado->categoria}, ideal para esta ocasión por su elegancia y estilo.\n\n";
        $recomendacion .= "📏 Talla: {$ternoRecomendado->talla}\n";
        $recomendacion .= "🎨 Color: {$ternoRecomendado->color}\n";
        $recomendacion .= "💰 Precio: S/ {$ternoRecomendado->precio_alquiler} por día";
        
        return response()->json([
            'success' => true,
            'recomendacion' => $recomendacion,
            'ternos' => $ternos
        ]);
    }
}