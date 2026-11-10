<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Enums\ModalidadTarifa;
use App\Services\Pricing\PriceCalculator;
use PHPUnit\Framework\TestCase;
use Tests\Support\Lines;

/** T01: tarifa bajo demanda con separación exacta entre pago mensual y pago único. */
class T01PrecioBajoDemandaTest extends TestCase
{
    public function test_separa_mensual_y_unico_en_centimos_exactos(): void
    {
        $quote = (new PriceCalculator())->quote(Lines::tienda(), ModalidadTarifa::OnDemand);

        // Mensual: 3333*2 + 12050 + 999*3 + 1800 = 6666 + 12050 + 2997 + 1800
        $this->assertSame(23513, $quote->monthlyTotalCents);
        // Único: 2500 + 1000
        $this->assertSame(3500, $quote->oneTimeTotalCents);
        // Primer año: 23513*12 + 3500
        $this->assertSame(285656, $quote->firstYearTotalCents());
        $this->assertSame(6666, $quote->lines[0]->monthlyCents);
    }

    public function test_una_linea_no_disponible_no_suma_importe(): void
    {
        $lines = Lines::tienda();
        $lines[1] = Lines::make(['itemId' => 2, 'productName' => 'Base de datos gestionada', 'typeSlug' => 'database',
            'available' => false, 'unitMonthlyCents' => 12050, 'unitSetupCents' => 2500]);

        $quote = (new PriceCalculator())->quote($lines, ModalidadTarifa::OnDemand);

        $this->assertSame(11463, $quote->monthlyTotalCents); // 23513 - 12050
        $this->assertSame(1000, $quote->oneTimeTotalCents);
    }
}
