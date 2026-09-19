<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pacientes', function (Blueprint $table) {
            $table->string('tipo_sangre', 5)->nullable()->after('genero');
            $table->json('enfermedades')->nullable()->after('alergias');
            $table->text('medicacion')->nullable()->after('enfermedades');
            $table->json('habitos')->nullable()->after('medicacion');
            $table->text('antecedentes_notas')->nullable()->after('habitos');
        });

        Schema::create('consentimientos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained('pacientes')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('tipo')->default('general');
            $table->string('titulo');
            $table->text('contenido');
            $table->boolean('firmado')->default(false);
            $table->date('fecha_firma')->nullable();
            $table->string('firmante')->nullable();
            $table->timestamps();

            $table->index(['paciente_id', 'firmado']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consentimientos');

        Schema::table('pacientes', function (Blueprint $table) {
            $table->dropColumn(['tipo_sangre', 'enfermedades', 'medicacion', 'habitos', 'antecedentes_notas']);
        });
    }
};
