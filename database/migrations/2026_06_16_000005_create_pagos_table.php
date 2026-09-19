<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained('pacientes')->cascadeOnDelete();
            $table->foreignId('presupuesto_id')->nullable()->constrained('presupuestos')->nullOnDelete();
            $table->date('fecha');
            $table->decimal('monto', 10, 2);
            $table->enum('metodo', ['efectivo', 'tarjeta', 'transferencia', 'qr'])->default('efectivo');
            $table->string('referencia')->nullable();
            $table->text('notas')->nullable();
            $table->timestamps();

            $table->index('fecha');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
