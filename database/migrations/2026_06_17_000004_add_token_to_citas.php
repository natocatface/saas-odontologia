<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('citas', function (Blueprint $table) {
            $table->string('token', 64)->nullable()->unique()->after('id');
            $table->timestamp('recordatorio_enviado_en')->nullable()->after('notas');
        });

        // Generar token para las citas existentes.
        foreach (DB::table('citas')->whereNull('token')->pluck('id') as $id) {
            DB::table('citas')->where('id', $id)->update(['token' => Str::random(40)]);
        }
    }

    public function down(): void
    {
        Schema::table('citas', function (Blueprint $table) {
            $table->dropUnique(['token']);
            $table->dropColumn(['token', 'recordatorio_enviado_en']);
        });
    }
};
