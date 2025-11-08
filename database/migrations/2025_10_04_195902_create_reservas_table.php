<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->foreignId('terno_id')->constrained('ternos')->onDelete('cascade');
            $table->date('fecha_reserva');
            $table->date('fecha_evento');
            $table->date('fecha_devolucion');
            $table->decimal('monto_total', 8, 2);
            $table->decimal('adelanto', 8, 2)->default(0);
            $table->decimal('saldo', 8, 2);
            $table->enum('estado', ['Pendiente', 'Confirmada', 'Entregada', 'Devuelta', 'Cancelada'])->default('Pendiente');
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};