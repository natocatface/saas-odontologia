<!DOCTYPE html>
<html lang="es">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"></head>
<body style="margin:0;background:#f1f5f9;font-family:Arial,Helvetica,sans-serif;color:#1e293b;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f1f5f9;padding:24px 0;">
        <tr><td align="center">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:520px;background:#ffffff;border-radius:16px;overflow:hidden;border:1px solid #e2e8f0;">
                <tr>
                    <td style="background:linear-gradient(135deg,#2563eb,#4f46e5);padding:24px 28px;color:#ffffff;">
                        <p style="margin:0;font-size:13px;color:#c7d2fe;">{{ $config['nombre_clinica'] ?? 'OdontoCRM' }}</p>
                        <h1 style="margin:4px 0 0;font-size:20px;">Recordatorio de tu cita</h1>
                    </td>
                </tr>
                <tr>
                    <td style="padding:28px;">
                        <p style="margin:0 0 16px;font-size:15px;">Hola{{ $cita->paciente ? ' '.$cita->paciente->nombre : '' }},</p>
                        <p style="margin:0 0 20px;font-size:15px;line-height:1.5;color:#475569;">
                            {{ $config['mensaje_recordatorio'] ?? 'Le recordamos su proxima cita. Por favor confirme su asistencia.' }}
                        </p>

                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;margin-bottom:24px;">
                            <tr><td style="padding:14px 18px;font-size:14px;">
                                <strong>Fecha:</strong> {{ $cita->fecha->translatedFormat('l d \d\e F \d\e Y') }}<br>
                                <strong>Hora:</strong> {{ $cita->hora ? \Illuminate\Support\Str::of($cita->hora)->substr(0,5) : 'Por confirmar' }}<br>
                                <strong>Doctor:</strong> {{ $cita->doctor->name ?? 'Por asignar' }}
                                @if($cita->motivo)<br><strong>Motivo:</strong> {{ $cita->motivo }}@endif
                            </td></tr>
                        </table>

                        <table role="presentation" cellpadding="0" cellspacing="0" style="margin:0 auto 8px;">
                            <tr><td style="border-radius:10px;background:#059669;">
                                <a href="{{ $url }}" style="display:inline-block;padding:13px 28px;font-size:15px;font-weight:bold;color:#ffffff;text-decoration:none;border-radius:10px;">Confirmar mi cita</a>
                            </td></tr>
                        </table>
                        <p style="margin:12px 0 0;font-size:12px;color:#94a3b8;text-align:center;">Si el boton no funciona, copia este enlace:<br>{{ $url }}</p>
                    </td>
                </tr>
                <tr>
                    <td style="padding:16px 28px;border-top:1px solid #e2e8f0;font-size:12px;color:#94a3b8;">
                        @if(!empty($config['telefono']))Telefono: {{ $config['telefono'] }} · @endif{{ $config['nombre_clinica'] ?? 'OdontoCRM' }}
                    </td>
                </tr>
            </table>
        </td></tr>
    </table>
</body>
</html>
