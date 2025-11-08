@extends('layouts.app')

@section('title', 'Asistente IA - TernoFit')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-robot text-indigo-600"></i> Asistente Virtual con IA
        </h1>
        <p class="text-gray-600 mt-2">Tu asistente inteligente powered by OpenAI GPT-3.5</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Chatbot -->
        <div class="bg-white rounded-xl shadow-lg">
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white p-6 rounded-t-xl">
                <h2 class="text-2xl font-bold">
                    <i class="fas fa-comments"></i> Chat con IA
                </h2>
                <p class="text-sm opacity-90 mt-1">Pregunta lo que necesites sobre TernoFit</p>
            </div>

            <div id="chatContainer" class="p-6 h-96 overflow-y-auto space-y-4 bg-gray-50">
                <div class="flex items-start space-x-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-robot text-white"></i>
                    </div>
                    <div class="bg-white rounded-lg p-3 max-w-md shadow">
                        <p class="text-gray-800">¡Hola! 👋 Soy tu asistente virtual de TernoFit. Puedo ayudarte con información sobre nuestros ternos, precios, disponibilidad y más. ¿En qué puedo ayudarte hoy?</p>
                    </div>
                </div>
            </div>

            <div class="p-6 border-t bg-white rounded-b-xl">
                <form id="chatForm" class="flex space-x-2">
                    @csrf
                    <input 
                        type="text" 
                        id="mensajeInput" 
                        placeholder="Escribe tu mensaje..."
                        class="flex-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500"
                        required
                    >
                    <button 
                        type="submit"
                        id="enviarBtn"
                        class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>

        <!-- Recomendador -->
        <div class="bg-white rounded-xl shadow-lg">
            <div class="bg-gradient-to-r from-green-600 to-teal-600 text-white p-6 rounded-t-xl">
                <h2 class="text-2xl font-bold">
                    <i class="fas fa-magic"></i> Recomendador Inteligente
                </h2>
                <p class="text-sm opacity-90 mt-1">Encuentra el terno perfecto con IA</p>
            </div>

            <div class="p-6">
                <form id="recomendadorForm">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-calendar-day text-green-600"></i> ¿Para qué ocasión?
                            </label>
                            <input 
                                type="text" 
                                name="ocasion" 
                                placeholder="Ej: Boda, Graduación, Evento corporativo..."
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500"
                                required
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-ruler text-green-600"></i> Talla
                            </label>
                            <select name="talla" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                                <option value="">Cualquier talla</option>
                                <option value="S">S - Small</option>
                                <option value="M">M - Medium</option>
                                <option value="L">L - Large</option>
                                <option value="XL">XL - Extra Large</option>
                                <option value="XXL">XXL</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-dollar-sign text-green-600"></i> Presupuesto máximo (S/)
                            </label>
                            <input 
                                type="number" 
                                name="presupuesto" 
                                placeholder="Ej: 200"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500"
                            >
                        </div>

                        <button 
                            type="submit"
                            id="recomendarBtn"
                            class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition font-semibold">
                            <i class="fas fa-search mr-2"></i>Obtener Recomendación con IA
                        </button>
                    </div>
                </form>

                <div id="recomendacionResult" class="mt-6 hidden">
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <h3 class="font-bold text-gray-800 mb-2 flex items-center">
                            <i class="fas fa-brain text-green-600 mr-2"></i>Recomendación de IA:
                        </h3>
                        <p id="recomendacionTexto" class="text-gray-700 whitespace-pre-line"></p>
                    </div>

                    <div id="ternosRecomendados" class="mt-4 space-y-2">
                        <!-- Ternos recomendados aparecerán aquí -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Chat con IA
document.getElementById('chatForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const input = document.getElementById('mensajeInput');
    const mensaje = input.value.trim();
    const container = document.getElementById('chatContainer');
    const btn = document.getElementById('enviarBtn');
    
    if (!mensaje) return;
    
    // Agregar mensaje del usuario
    container.innerHTML += `
        <div class="flex items-start space-x-3 justify-end">
            <div class="bg-indigo-600 text-white rounded-lg p-3 max-w-md shadow">
                <p>${mensaje}</p>
            </div>
            <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-user text-gray-600"></i>
            </div>
        </div>
    `;
    
    input.value = '';
    container.scrollTop = container.scrollHeight;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    
    try {
        const response = await fetch('{{ route("ia.chat") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ mensaje })
        });
        
        const data = await response.json();
        
        if (data.success) {
            container.innerHTML += `
                <div class="flex items-start space-x-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-robot text-white"></i>
                    </div>
                    <div class="bg-white rounded-lg p-3 max-w-md shadow">
                        <p class="text-gray-800">${data.respuesta}</p>
                    </div>
                </div>
            `;
        } else {
            container.innerHTML += `
                <div class="flex items-start space-x-3">
                    <div class="w-10 h-10 rounded-full bg-red-500 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-exclamation text-white"></i>
                    </div>
                    <div class="bg-red-50 border border-red-200 rounded-lg p-3 max-w-md">
                        <p class="text-red-700">${data.error}</p>
                    </div>
                </div>
            `;
        }
    } catch (error) {
        alert('Error al enviar mensaje');
    }
    
    btn.disabled = false;
    btn.innerHTML = '<i class="fas fa-paper-plane"></i>';
    container.scrollTop = container.scrollHeight;
});

// Recomendador
document.getElementById('recomendadorForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    const btn = document.getElementById('recomendarBtn');
    
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Generando recomendación...';
    
    try {
        const response = await fetch('{{ route("ia.recomendar") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            document.getElementById('recomendacionResult').classList.remove('hidden');
            document.getElementById('recomendacionTexto').textContent = result.recomendacion;
            
            const ternosHtml = result.ternos.map(terno => `
                <div class="border rounded-lg p-3 hover:bg-gray-50 transition">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-semibold text-gray-800">${terno.codigo} - ${terno.marca}</p>
                            <p class="text-sm text-gray-600">${terno.categoria} | Talla: ${terno.talla} | Color: ${terno.color}</p>
                        </div>
                        <p class="text-green-600 font-bold text-lg">S/ ${terno.precio_alquiler}</p>
                    </div>
                </div>
            `).join('');
            
            document.getElementById('ternosRecomendados').innerHTML = ternosHtml || '<p class="text-gray-500 text-center">No hay ternos disponibles</p>';
        } else {
            alert(result.error);
        }
    } catch (error) {
        alert('Error al obtener recomendación');
    }
    
    btn.disabled = false;
    btn.innerHTML = '<i class="fas fa-search mr-2"></i>Obtener Recomendación con IA';
});
</script>
@endsection