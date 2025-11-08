<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\TernoController;
use App\Http\Controllers\ReservaController;

// Rutas públicas
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// Rutas protegidas
Route::middleware(['auth'])->group(function () {
    
    // Rutas de IA
    Route::get('/ia', [App\Http\Controllers\IAController::class, 'index'])->name('ia.index');
    Route::post('/ia/chat', [App\Http\Controllers\IAController::class, 'chat'])->name('ia.chat');
    Route::post('/ia/recomendar', [App\Http\Controllers\IAController::class, 'recomendarTerno'])->name('ia.recomendar');
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Rutas de perfil
    Route::get('/perfil', [PerfilController::class, 'index'])->name('perfil');
    Route::get('/mis-estadisticas', [PerfilController::class, 'estadisticas'])->name('mis-estadisticas');

    // Rutas solo para Admin
    Route::middleware(['admin'])->group(function () {
        Route::get('/admin/empleados', [AdminController::class, 'empleados'])->name('admin.empleados');
        Route::get('/admin/asignar-tareas', [AdminController::class, 'asignarTareas'])->name('admin.asignar-tareas');
        Route::post('/admin/tareas/store', [AdminController::class, 'storeTarea'])->name('admin.tareas.store');
    });

    // Rutas solo para Empleados
    Route::get('/empleado/tareas', [EmpleadoController::class, 'tareas'])->name('empleado.tareas');
    Route::post('/empleado/tareas/{id}/actualizar', [EmpleadoController::class, 'actualizarTarea'])->name('empleado.tareas.actualizar');

    // CRUD de Usuarios (debe estar dentro de middleware auth)
    Route::resource('usuarios', App\Http\Controllers\UserController::class);

    // CRUD Clientes
    Route::resource('clientes', ClienteController::class);

    // CRUD Ternos
    Route::resource('ternos', TernoController::class);

    // CRUD Reservas
    Route::resource('reservas', ReservaController::class);

    // API para búsqueda inteligente de clientes
    Route::get('/api/buscar-cliente', [App\Http\Controllers\ReservaController::class, 'buscarCliente'])->name('api.buscar-cliente');
    Route::post('/api/crear-cliente-rapido', [App\Http\Controllers\ReservaController::class, 'crearClienteRapido'])->name('api.crear-cliente-rapido');

    // Reportes
    Route::get('/reportes', [App\Http\Controllers\ReporteController::class, 'index'])->name('reportes.index');
    Route::get('/reportes/excel', [App\Http\Controllers\ReporteController::class, 'exportarExcel'])->name('reportes.excel');
    Route::get('/reportes/pdf', [App\Http\Controllers\ReporteController::class, 'exportarPDF'])->name('reportes.pdf');

});