<?php

declare(strict_types=1);

namespace App\Services\Rules;

use App\Enums\Severidad;
use App\Services\Architecture\ArchitectureSnapshot;
use App\Services\Pricing\Quote;

/** R02 (BLOQUEA): la cantidad de cada línea debe estar entre 1 y 100. */
final class QuantityInRangeRule implements BusinessRule
{
    public const MIN = 1;
    public const MAX = 100;

    public function code(): string
    {
        return 'R02';
    }

    public function evaluate(ArchitectureSnapshot $architecture, Quote $quote): array
    {
        $results = [];
        foreach ($architecture->lines as $line) {
            if ($line->quantity < self::MIN || $line->quantity > self::MAX) {
                $results[] = new RuleResult(
                    $this->code(),
                    Severidad::Block,
                    "La cantidad de «{$line->productName}» ({$line->quantity}) está fuera del rango permitido ("
                        . self::MIN . '–' . self::MAX . ').',
                    $line->itemId,
                );
            }
        }

        return $results;
    }
}
