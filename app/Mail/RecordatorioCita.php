<?php

namespace App\Mail;

use App\Models\Cita;
use App\Models\Configuracion;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RecordatorioCita extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Cita $cita)
    {
    }

    public function envelope(): Envelope
    {
        $clinica = Configuracion::valor('nombre_clinica', 'OdontoCRM');

        return new Envelope(
            subject: 'Recordatorio de tu cita en '.$clinica,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.recordatorio-cita',
            with: [
                'cita' => $this->cita,
                'config' => Configuracion::todas(),
                'url' => $this->cita->url_confirmacion,
            ],
        );
    }
}
