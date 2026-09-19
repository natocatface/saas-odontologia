<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gastos', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->string('categoria')->default('otros');
            $table->string('descripcion');
            $table->decimal('monto', 10, 2);
            $table->enum('metodo', ['efectivo', 'tarjeta', 'transferencia', 'qr'])->default('efectivo');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('fecha');
            $table->index('categoria');
        });

        // Porcentaje de comision del doctor sobre lo cobrado.
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('comision', 5, 2)->default(0)->after('especialidad');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gastos');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('comision');
        });
    }
};
