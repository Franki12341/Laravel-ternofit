<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ternos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->string('marca');
            $table->string('talla');
            $table->string('color');
            $table->enum('categoria', ['Clásico', 'Moderno', 'Premium', 'Infantil']);
            $table->decimal('precio_alquiler', 8, 2);
            $table->enum('estado', ['Disponible', 'Alquilado', 'Mantenimiento'])->default('Disponible');
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ternos');
    }
};