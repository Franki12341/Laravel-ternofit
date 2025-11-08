<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('actividades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('tipo'); // reserva_creada, cliente_registrado, terno_agregado
            $table->text('descripcion');
            $table->decimal('valor', 10, 2)->nullable(); // monto si aplica
            $table->timestamps();
        });

        // Tabla para asignaciones de tareas
        Schema::create('tareas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asignado_por')->constrained('users')->onDelete('cascade');
            $table->foreignId('asignado_a')->constrained('users')->onDelete('cascade');
            $table->string('titulo');
            $table->text('descripcion');
            $table->enum('prioridad', ['baja', 'media', 'alta'])->default('media');
            $table->enum('estado', ['pendiente', 'en_proceso', 'completada'])->default('pendiente');
            $table->date('fecha_vencimiento')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tareas');
        Schema::dropIfExists('actividades');
    }
};