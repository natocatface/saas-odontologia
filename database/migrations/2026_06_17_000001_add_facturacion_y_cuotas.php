<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Numero de recibo correlativo para cada pago.
        Schema::table('pagos', function (Blueprint $table) {
            $table->string('numero')->nullable()->unique()->after('id');
        });

        // Plan de pago en cuotas ligado a un presupuesto.
        Schema::create('cuotas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('presupuesto_id')->constrained('presupuestos')->cascadeOnDelete();
            $table->foreignId('pago_id')->nullable()->constrained('pagos')->nullOnDelete();
            $table->unsignedInteger('numero');
            $table->decimal('monto', 10, 2)->default(0);
            $table->date('vence_el');
            $table->boolean('pagada')->default(false);
            $table->timestamps();

            $table->index(['presupuesto_id', 'numero']);
            $table->index(['pagada', 'vence_el']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cuotas');

        Schema::table('pagos', function (Blueprint $table) {
            $table->dropUnique(['numero']);
            $table->dropColumn('numero');
        });
    }
};
