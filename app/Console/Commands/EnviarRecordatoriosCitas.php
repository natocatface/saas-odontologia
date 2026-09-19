<?php

namespace App\Console\Commands;

use App\Mail\RecordatorioCita;
use App\Models\Cita;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class EnviarRecordatoriosCitas extends Command
{
    protected $signature = 'citas:recordatorios {--dias=1 : Dias de anticipacion} {--reenviar : Incluir citas con recordatorio ya enviado}';

    protected $description = 'Envia recordatorios por email de las citas proximas (por defecto, las del dia siguiente).';

    public function handle(): int
    {
        $dias = (int) $this->option('dias');
        $fecha = Carbon::today()->addDays($dias)->toDateString();

        $citas = Cita::with(['paciente', 'doctor'])
            ->whereDate('fecha', $fecha)
            ->whereIn('estado', ['pendiente', 'confirmada'])
            ->when(! $this->option('reenviar'), fn ($q) => $q->whereNull('recordatorio_enviado_en'))
            ->get();

        $enviados = 0;
        $omitidos = 0;

        foreach ($citas as $cita) {
            if (empty($cita->paciente?->email)) {
                $omitidos++;
                continue;
            }

            try {
                Mail::to($cita->paciente->email)->send(new RecordatorioCita($cita));
                $cita->update(['recordatorio_enviado_en' => now()]);
                $enviados++;
            } catch (\Throwable $e) {
                $this->error("Cita #{$cita->id}: ".$e->getMessage());
            }
        }

        $this->info("Recordatorios para {$fecha}: {$enviados} enviado(s), {$omitidos} sin correo.");

        return self::SUCCESS;
    }
}
