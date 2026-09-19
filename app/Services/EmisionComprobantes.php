<?php

namespace App\Services;

use App\Facturacion\Application\DTO\EmitirFacturaDTO;
use App\Facturacion\Application\Services\ServicioFacturacion;
use App\Facturacion\Domain\Result\ResultadoEmision;
use App\Models\FacturacionConfig;
use App\Models\Pago;

/**
 * Puente entre el ERP (Pagos) y el bounded context de Facturacion.
 *
 * Traduce un Pago del sistema dental a un EmitirFacturaDTO neutral y lo
 * entrega a la fachada ServicioFacturacion. No contiene logica de SUNAT:
 * solo decide tipo de comprobante, serie y arma el receptor + la linea.
 */
final class EmisionComprobantes
{
    /** IGV general de Peru. */
    private const IGV = 0.18;

    public function __construct(
        private readonly ServicioFacturacion $servicio,
    ) {
    }

    /** ¿La configuracion pide emitir automaticamente al registrar el pago? */
    public function debeEmitirAuto(): bool
    {
        return FacturacionConfig::habilitada()
            && FacturacionConfig::autoEmitir()
            && FacturacionConfig::driver() !== 'ninguno';
    }

    /**
     * Emite el comprobante correspondiente a un pago.
     * Devuelve null si la facturacion no esta habilitada.
     */
    public function emitirDesdePago(Pago $pago): ?ResultadoEmision
    {
        if (! FacturacionConfig::habilitada() || FacturacionConfig::driver() === 'ninguno') {
            return null;
        }

        $pago->loadMissing('paciente', 'presupuesto');

        $dto = new EmitirFacturaDTO(
            empresaId: 1,
            pais: 'PE',
            tipo: $this->tipoComprobante($pago),
            receptor: $this->receptor($pago),
            items: $this->items($pago),
            moneda: 'PEN',
            serie: $this->serie($pago),
            origen: ['tipo' => 'pago', 'id' => $pago->id],
        );

        return $this->servicio->emitirFactura($dto);
    }

    /** RUC (11 digitos) => factura; en otro caso => boleta. */
    private function tipoComprobante(Pago $pago): string
    {
        $doc = preg_replace('/\D/', '', (string) ($pago->paciente->documento ?? ''));

        return strlen((string) $doc) === 11 ? 'factura' : 'boleta';
    }

    private function serie(Pago $pago): string
    {
        return $this->tipoComprobante($pago) === 'factura' ? 'F001' : 'B001';
    }

    /** @return array<string,mixed> */
    private function receptor(Pago $pago): array
    {
        $paciente = $pago->paciente;
        $doc = preg_replace('/\D/', '', (string) ($paciente->documento ?? ''));
        $razon = trim(($paciente->apellido ?? '').' '.($paciente->nombre ?? '')) ?: 'CLIENTE VARIOS';

        if (strlen((string) $doc) === 11) {
            $tipo = 'RUC';
        } elseif (strlen((string) $doc) === 8) {
            $tipo = 'DNI';
        } else {
            $tipo = 'DNI';
            $doc = $doc !== '' ? $doc : '00000000';
        }

        return [
            'identificacion'     => $doc,
            'tipoIdentificacion' => $tipo,
            'razonSocial'        => mb_strtoupper($razon),
            'direccion'          => $paciente->direccion ?? null,
            'email'              => $paciente->email ?? null,
        ];
    }

    /**
     * Una linea con el importe del pago. El monto del pago se considera
     * CON IGV incluido, por lo que se calcula la base (precio sin IGV).
     *
     * @return array<int,array<string,mixed>>
     */
    private function items(Pago $pago): array
    {
        $total = (float) $pago->monto;
        $base = round($total / (1 + self::IGV), 2);

        $descripcion = $pago->presupuesto_id
            ? 'Atencion odontologica - Presupuesto #'.$pago->presupuesto_id
            : 'Servicios odontologicos';

        return [[
            'descripcion' => $descripcion,
            'cantidad'    => 1,
            'precio'      => $base,
            'tasa'        => self::IGV,
            'codigo'      => 'SERV-ODONT',
        ]];
    }
}
