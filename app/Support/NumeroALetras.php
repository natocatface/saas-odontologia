<?php

namespace App\Support;

/** Convierte importes a letras en espanol (para la leyenda del comprobante). */
final class NumeroALetras
{
    public static function importe(float $monto, string $moneda = 'PEN'): string
    {
        $entero = (int) floor($monto);
        $centavos = (int) round(($monto - $entero) * 100);
        $texto = $entero === 0 ? 'CERO' : self::enteroEnLetras($entero);
        $sufijo = match (strtoupper($moneda)) {
            'USD' => 'DOLARES AMERICANOS',
            'EUR' => 'EUROS',
            default => 'SOLES',
        };

        return trim($texto).' CON '.str_pad((string) $centavos, 2, '0', STR_PAD_LEFT).'/100 '.$sufijo;
    }

    public static function enteroEnLetras(int $n): string
    {
        if ($n === 0) {
            return '';
        }
        if ($n < 0) {
            return 'MENOS '.self::enteroEnLetras(-$n);
        }

        $unidades = ['', 'UNO', 'DOS', 'TRES', 'CUATRO', 'CINCO', 'SEIS', 'SIETE', 'OCHO', 'NUEVE',
            'DIEZ', 'ONCE', 'DOCE', 'TRECE', 'CATORCE', 'QUINCE', 'DIECISEIS', 'DIECISIETE',
            'DIECIOCHO', 'DIECINUEVE', 'VEINTE'];
        $decenas = ['', '', 'VEINTI', 'TREINTA', 'CUARENTA', 'CINCUENTA', 'SESENTA', 'SETENTA', 'OCHENTA', 'NOVENTA'];
        $centenas = ['', 'CIENTO', 'DOSCIENTOS', 'TRESCIENTOS', 'CUATROCIENTOS', 'QUINIENTOS',
            'SEISCIENTOS', 'SETECIENTOS', 'OCHOCIENTOS', 'NOVECIENTOS'];

        if ($n <= 20) {
            return $unidades[$n];
        }
        if ($n < 30) {
            return $decenas[2].($n % 10 ? $unidades[$n % 10] : '');
        }
        if ($n < 100) {
            $d = intdiv($n, 10);
            $u = $n % 10;

            return $decenas[$d].($u ? ' Y '.$unidades[$u] : '');
        }
        if ($n === 100) {
            return 'CIEN';
        }
        if ($n < 1000) {
            $c = intdiv($n, 100);
            $resto = $n % 100;

            return $centenas[$c].($resto ? ' '.self::enteroEnLetras($resto) : '');
        }
        if ($n < 1000000) {
            $miles = intdiv($n, 1000);
            $resto = $n % 1000;
            $prefijo = $miles === 1 ? 'MIL' : self::enteroEnLetras($miles).' MIL';

            return $prefijo.($resto ? ' '.self::enteroEnLetras($resto) : '');
        }

        $millones = intdiv($n, 1000000);
        $resto = $n % 1000000;
        $prefijo = $millones === 1 ? 'UN MILLON' : self::enteroEnLetras($millones).' MILLONES';

        return $prefijo.($resto ? ' '.self::enteroEnLetras($resto) : '');
    }
}
