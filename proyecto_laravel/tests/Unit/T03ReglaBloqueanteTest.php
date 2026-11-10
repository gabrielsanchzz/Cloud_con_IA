<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Enums\ModalidadTarifa;
use App\Enums\Severidad;
use App\Services\Architecture\ArchitectureSnapshot;
use App\Services\Pricing\PriceCalculator;
use App\Services\Rules\RuleEngine;
use PHPUnit\Framework\TestCase;
use Tests\Support\Lines;

/** T03: reglas que BLOQUEAN el presupuesto (R01 región, R02 cantidad, R03 presupuesto). */
class T03ReglaBloqueanteTest extends TestCase
{
    private function evaluate(array $lines, ?int $budget, ModalidadTarifa $model = ModalidadTarifa::OnDemand)
    {
        $snapshot = new ArchitectureSnapshot($budget, $lines);
        $quote = (new PriceCalculator())->quote($lines, $model);

        return RuleEngine::default()->evaluate($snapshot, $quote);
    }

    public function test_r01_producto_fuera_de_region_bloquea_con_motivo_comprensible(): void
    {
        $lines = Lines::tienda();
        $lines[1] = Lines::make(['itemId' => 2, 'productName' => 'Base de datos gestionada', 'typeSlug' => 'database',
            'regionName' => 'Tokio', 'available' => false]);

        $report = $this->evaluate($lines, 100000);

        $this->assertTrue($report->isBlocked());
        $this->assertFalse($report->canIssueBudget());
        $this->assertContains('R01', $report->codes());
        $this->assertSame(Severidad::Block, $report->blocking()[0]->severity);
        $this->assertStringContainsString('Base de datos gestionada', $report->blocking()[0]->message);
        $this->assertStringContainsString('Tokio', $report->blocking()[0]->message);
    }

    public function test_r02_cantidad_en_el_limite_es_valida_y_fuera_de_rango_bloquea(): void
    {
        $ok = Lines::tienda();
        $ok[2] = Lines::make(['itemId' => 3, 'productName' => 'Almacenamiento de objetos', 'typeSlug' => 'storage', 'quantity' => 100, 'unitMonthlyCents' => 1]);
        $this->assertNotContains('R02', $this->evaluate($ok, 10000000)->codes()); // 100 permitido

        foreach ([101, 0] as $badQuantity) {
            $bad = Lines::tienda();
            $bad[2] = Lines::make(['itemId' => 3, 'productName' => 'Almacenamiento de objetos', 'typeSlug' => 'storage', 'quantity' => $badQuantity, 'unitMonthlyCents' => 1]);
            $report = $this->evaluate($bad, 10000000);
            $this->assertContains('R02', $report->codes());
            $this->assertTrue($report->isBlocked());
        }
    }

    public function test_r03_fuera_de_presupuesto_bloquea_y_otra_tarifa_lo_resuelve(): void
    {
        // Presupuesto 200,00 €/mes: bajo demanda cuesta 235,13 € -> bloquea
        $report = $this->evaluate(Lines::tienda(), 20000);
        $this->assertContains('R03', $report->codes());
        $this->assertStringContainsString('35,13 €', $report->blocking()[0]->message);

        // Con reserva 12 meses cuesta 164,59 € -> cabe
        $this->assertNotContains('R03', $this->evaluate(Lines::tienda(), 20000, ModalidadTarifa::Reserved12)->codes());
    }
}
