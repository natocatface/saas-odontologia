<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('insumos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('categoria')->default('general');
            $table->string('unidad')->default('unidad');
            $table->decimal('stock', 10, 2)->default(0);
            $table->decimal('stock_minimo', 10, 2)->default(0);
            $table->decimal('costo_unitario', 10, 2)->nullable();
            $table->string('proveedor')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->index('categoria');
        });

        Schema::create('movimientos_inventario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('insumo_id')->constrained('insumos')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('tipo', ['entrada', 'salida', 'ajuste']);
            $table->decimal('cantidad', 10, 2);
            $table->decimal('stock_resultante', 10, 2)->default(0);
            $table->string('motivo')->nullable();
            $table->date('fecha');
            $table->timestamps();

            $table->index(['insumo_id', 'fecha']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimientos_inventario');
        Schema::dropIfExists('insumos');
    }
};
