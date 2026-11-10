<?php

declare(strict_types=1);

namespace App\Services\Rules;

use App\Enums\Severidad;
use App\Services\Architecture\ArchitectureSnapshot;
use App\Services\Pricing\Quote;
use App\Support\Money;

/** R03 (BLOQUEA): el coste MENSUAL no puede superar el presupuesto mensual del proyecto. */
final class WithinBudgetRule implements BusinessRule
{
    public function code(): string
    {
        return 'R03';
    }

    public function evaluate(ArchitectureSnapshot $architecture, Quote $quote): array
    {
        $budget = $architecture->monthlyBudgetCents;
        if ($budget === null || $quote->monthlyTotalCents <= $budget) {
            return [];
        }

        $excess = $quote->monthlyTotalCents - $budget;

        return [new RuleResult(
            $this->code(),
            Severidad::Block,
            'El coste mensual (' . Money::format($quote->monthlyTotalCents) . ') supera el presupuesto del proyecto ('
                . Money::format($budget) . ') en ' . Money::format($excess) . '. Reduce cantidades o prueba otra tarifa.',
        )];
    }
}
