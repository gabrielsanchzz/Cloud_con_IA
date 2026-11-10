<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Enums\ModalidadTarifa;
use App\Services\Pricing\PriceCalculator;
use App\Support\Money;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Tests\Support\Lines;

/** T02: tarifas reservadas con cálculo exacto (redondeo half-up) y límites. */
class T02PrecioReservadoRedondeoTest extends TestCase
{
    public function test_reservada_12_meses_redondea_half_up_y_no_descuenta_el_pago_unico(): void
    {
        $quote = (new PriceCalculator())->quote(Lines::tienda(), ModalidadTarifa::Reserved12);

        // -30 %: 4666 + 8435 + 2098 + 1260
        $this->assertSame(16459, $quote->monthlyTotalCents);
        $this->assertSame(3500, $quote->oneTimeTotalCents); // sin descuento
    }

    public function test_reservada_36_meses_con_importes_exactos(): void
    {
        $quote = (new PriceCalculator())->quote(Lines::tienda(), ModalidadTarifa::Reserved36);

        // -45 %: 3666 + 6628 + 1648 + 990 (6628 = 6627,5 redondeado hacia arriba)
        $this->assertSame(12932, $quote->monthlyTotalCents);
    }

    public function test_el_redondeo_es_entero_y_sin_deriva_de_coma_flotante(): void
    {
        $this->assertSame(6999, Money::applyDiscountBp(9999, 3000)); // 6999,3 -> 6999
        $this->assertSame(5499, Money::applyDiscountBp(9999, 4500)); // 5499,45 -> 5499
        $this->assertSame(1, Money::applyDiscountBp(1, 4500));       // 0,55 -> 1
        $this->assertSame(0, Money::applyDiscountBp(0, 3000));
        $this->assertSame(1000, Money::applyDiscountBp(1000, 0));
    }

    public function test_limites_de_entrada_se_rechazan(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Money::applyDiscountBp(1000, 10001);
    }

    public function test_formato_de_importes(): void
    {
        $this->assertSame('235,13 €', Money::format(23513));
        $this->assertSame('1.586,84 €', Money::format(158684));
    }
}
