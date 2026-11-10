<?php

declare(strict_types=1);

namespace App\Services\Pricing;

use App\Enums\ModalidadTarifa;
use App\Support\Money;
use InvalidArgumentException;

/**
 * Único lugar donde se calcula el precio oficial. Se llama SIEMPRE desde el servidor
 * con datos del catálogo; los controladores nunca aceptan un total enviado por el cliente.
 *
 * Reglas de cálculo:
 *  - Importes en céntimos enteros.
 *  - El descuento de la modalidad se aplica al TOTAL MENSUAL DE CADA LÍNEA (unitario × cantidad),
 *    con redondeo half-up, para que el error máximo sea de 1 céntimo por línea.
 *  - El pago único (alta/instalación) NO se descuenta y se mantiene separado del mensual.
 *  - Las líneas no disponibles en la región no suman (las bloquea la regla R01).
 */
final class PriceCalculator
{
    /** @param list<LineInput> $lines */
    public function quote(array $lines, ModalidadTarifa $model): Quote
    {
        $lineQuotes = [];
        $monthlyTotal = 0;
        $oneTimeTotal = 0;

        foreach ($lines as $line) {
            if ($line->quantity < 0 || $line->unitMonthlyCents < 0 || $line->unitSetupCents < 0) {
                throw new InvalidArgumentException("Valores negativos en la línea de «{$line->productName}».");
            }

            if (! $line->available) {
                $lineQuotes[] = new LineQuote($line, 0, 0);
                continue;
            }

            $monthly = Money::applyDiscountBp($line->unitMonthlyCents * $line->quantity, $model->discountBp());
            $oneTime = $line->unitSetupCents * $line->quantity;

            $lineQuotes[] = new LineQuote($line, $monthly, $oneTime);
            $monthlyTotal += $monthly;
            $oneTimeTotal += $oneTime;
        }

        return new Quote($model, $lineQuotes, $monthlyTotal, $oneTimeTotal);
    }
}
