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

/** T04: reglas que solo AVISAN (R04 backup, R05 multirregión): se puede continuar. */
class T04ReglaAvisoTest extends TestCase
{
    public function test_sin_backup_y_una_sola_region_avisa_pero_no_bloquea(): void
    {
        $lines = Lines::tienda();
        $snapshot = new ArchitectureSnapshot(100000, $lines);
        $quote = (new PriceCalculator())->quote($lines, ModalidadTarifa::OnDemand);

        $report = RuleEngine::default()->evaluate($snapshot, $quote);

        $this->assertFalse($report->isBlocked());
        $this->assertTrue($report->canIssueBudget());
        $this->assertTrue($report->hasWarnings());
        $this->assertSame(['R04', 'R05'], $report->codes());
        $this->assertSame(Severidad::Warning, $report->warnings()[0]->severity);
        $this->assertStringContainsString('copia de seguridad', $report->warnings()[0]->message);
    }

    public function test_con_backup_y_dos_regiones_no_hay_avisos(): void
    {
        $lines = Lines::tienda();
        $lines[] = Lines::make(['itemId' => 5, 'productName' => 'Backup diario', 'typeSlug' => 'backup',
            'regionId' => 2, 'regionName' => 'Fráncfort', 'unitMonthlyCents' => 500]);
        $snapshot = new ArchitectureSnapshot(100000, $lines);
        $quote = (new PriceCalculator())->quote($lines, ModalidadTarifa::OnDemand);

        $report = RuleEngine::default()->evaluate($snapshot, $quote);

        $this->assertSame([], $report->codes());
        $this->assertTrue($report->canIssueBudget());
    }
}
