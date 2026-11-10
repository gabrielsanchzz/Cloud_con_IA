<?php

declare(strict_types=1);

namespace App\Services\Rules;

use App\Services\Architecture\ArchitectureSnapshot;
use App\Services\Pricing\Quote;

final class RuleEngine
{
    /** @param list<BusinessRule> $rules */
    public function __construct(private readonly array $rules)
    {
    }

    /** Conjunto oficial: R01-R03 bloquean; R04-R05 solo avisan. */
    public static function default(): self
    {
        return new self([
            new ProductAvailableInRegionRule(),
            new QuantityInRangeRule(),
            new WithinBudgetRule(),
            new BackupRecommendedRule(),
            new MultiRegionRecommendedRule(),
        ]);
    }

    public function evaluate(ArchitectureSnapshot $architecture, Quote $quote): RuleReport
    {
        $results = [];
        foreach ($this->rules as $rule) {
            array_push($results, ...$rule->evaluate($architecture, $quote));
        }

        return new RuleReport($results);
    }
}
