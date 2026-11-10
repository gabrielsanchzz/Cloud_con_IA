<?php

declare(strict_types=1);

namespace App\Services\Rules;

use App\Enums\Severidad;
use App\Services\Architecture\ArchitectureSnapshot;
use App\Services\Pricing\Quote;

/** R05 (AVISA): si todos los elementos están en una única región se recomienda redundancia. */
final class MultiRegionRecommendedRule implements BusinessRule
{
    public function code(): string
    {
        return 'R05';
    }

    public function evaluate(ArchitectureSnapshot $architecture, Quote $quote): array
    {
        $regions = $architecture->regionNames();
        if (count($architecture->lines) < 2 || count($regions) !== 1) {
            return [];
        }

        return [new RuleResult(
            $this->code(),
            Severidad::Warning,
            "Recomendación: todos los elementos están en una sola región ({$regions[0]}). Una caída regional dejaría la aplicación sin servicio.",
        )];
    }
}
