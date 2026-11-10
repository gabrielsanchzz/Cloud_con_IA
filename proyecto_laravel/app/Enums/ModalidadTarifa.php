<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Modalidades de tarifa. El descuento se expresa en puntos básicos (1 pb = 0,01 %)
 * para operar siempre con enteros y evitar errores de coma flotante.
 */
enum ModalidadTarifa: string
{
    case OnDemand = 'on_demand';
    case Reserved12 = 'reserved_12m';
    case Reserved36 = 'reserved_36m';

    /** Descuento sobre el pago MENSUAL recurrente (el pago único no se descuenta). */
    public function discountBp(): int
    {
        return match ($this) {
            self::OnDemand => 0,
            self::Reserved12 => 3000, // -30 %
            self::Reserved36 => 4500, // -45 %
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::OnDemand => 'Bajo demanda',
            self::Reserved12 => 'Reservada 12 meses (-30 %)',
            self::Reserved36 => 'Reservada 36 meses (-45 %)',
        };
    }
}
