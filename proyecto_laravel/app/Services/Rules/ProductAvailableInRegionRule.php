<?php

declare(strict_types=1);

namespace App\Services\Rules;

use App\Enums\Severidad;
use App\Services\Architecture\ArchitectureSnapshot;
use App\Services\Pricing\Quote;

/** R01 (BLOQUEA): el producto debe estar disponible en la región elegida. */
final class ProductAvailableInRegionRule implements BusinessRule
{
    public function code(): string
    {
        return 'R01';
    }

    public function evaluate(ArchitectureSnapshot $architecture, Quote $quote): array
    {
        $results = [];
        foreach ($architecture->lines as $line) {
            if (! $line->available) {
                $results[] = new RuleResult(
                    $this->code(),
                    Severidad::Block,
                    "«{$line->productName}» no está disponible en la región {$line->regionName}. Elige otra región o sustituye el producto.",
                    $line->itemId,
                );
            }
        }

        return $results;
    }
}
