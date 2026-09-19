<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Almacen clave-valor para la configuracion de Facturacion Electronica.
 * Se mantiene separada de `configuraciones` para aislar el bounded context
 * de facturacion (App\Facturacion) y facilitar su extraccion a microservicio.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facturacion_configuraciones', function (Blueprint $table) {
            $table->id();
            $table->string('clave')->unique();
            $table->text('valor')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facturacion_configuraciones');
    }
};
