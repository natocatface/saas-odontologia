<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documentos_fiscales', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('empresa_id')->nullable()->index();
            $table->string('pais', 2)->index();
            $table->string('tipo');
            $table->string('serie')->nullable();
            $table->unsignedBigInteger('numero')->nullable();
            $table->json('emisor_snapshot')->nullable();
            $table->json('receptor_snapshot')->nullable();
            $table->string('moneda', 3)->default('PEN');
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('impuestos', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->string('estado')->default('borrador')->index();
            $table->string('referencia_externa')->nullable();
            $table->string('hash')->nullable();
            $table->string('idempotency_key')->nullable()->unique();
            $table->unsignedInteger('intentos')->default(0);
            $table->text('error')->nullable();
            $table->string('xml_path')->nullable();
            $table->string('cdr_path')->nullable();
            $table->string('pdf_path')->nullable();
            $table->string('origen_tipo')->nullable();
            $table->unsignedBigInteger('origen_id')->nullable();
            $table->timestamps();

            $table->index(['origen_tipo', 'origen_id']);
        });

        Schema::create('documento_lineas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('documento_fiscal_id')->constrained('documentos_fiscales')->cascadeOnDelete();
            $table->string('descripcion');
            $table->decimal('cantidad', 12, 3)->default(1);
            $table->decimal('precio', 12, 2)->default(0);
            $table->decimal('impuesto_tasa', 5, 4)->default(0);
            $table->decimal('impuesto_monto', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('documento_eventos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('documento_fiscal_id')->constrained('documentos_fiscales')->cascadeOnDelete();
            $table->string('tipo');
            $table->string('estado_anterior')->nullable();
            $table->string('estado_nuevo')->nullable();
            $table->json('payload')->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documento_eventos');
        Schema::dropIfExists('documento_lineas');
        Schema::dropIfExists('documentos_fiscales');
    }
};
