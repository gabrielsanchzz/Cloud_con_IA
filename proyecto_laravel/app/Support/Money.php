<?php

declare(strict_types=1);

namespace App\Support;

use InvalidArgumentException;

/** Utilidades de dinero. Todo el sistema trabaja en CÉNTIMOS enteros (int). */
final class Money
{
    /**
     * Aplica un descuento en puntos básicos con redondeo "half up" usando solo enteros.
     * Ej.: applyDiscountBp(9999, 3000) = intdiv(9999*7000 + 5000, 10000) = 6999.
     */
    public static function applyDiscountBp(int $cents, int $discountBp): int
    {
        if ($cents < 0) {
            throw new InvalidArgumentException('El importe no puede ser negativo.');
        }
        if ($discountBp < 0 || $discountBp > 10000) {
            throw new InvalidArgumentException('El descuento debe estar entre 0 y 10000 puntos básicos.');
        }

        return intdiv($cents * (10000 - $discountBp) + 5000, 10000);
    }

    /** Convierte un decimal de la BD (columna decimal(10,2): "12.40") en céntimos enteros (1240). */
    public static function fromDecimal(string|int|float|null $value): int
    {
        return (int) str_replace('.', '', number_format((float) $value, 2, '.', ''));
    }

    /** Solo para MOSTRAR: 23513 => "235,13 €". Nunca se usa para calcular. */
    public static function format(int $cents): string
    {
        $sign = $cents < 0 ? '-' : '';

        return $sign . number_format(abs($cents) / 100, 2, ',', '.') . ' €';
    }
}
