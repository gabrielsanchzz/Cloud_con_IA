<?php

declare(strict_types=1);

namespace App\Services\Rules;

use App\Services\Architecture\ArchitectureSnapshot;
use App\Services\Pricing\Quote;

interface BusinessRule
{
    public function code(): string;

    /** @return list<RuleResult> vacío si la regla se cumple */
    public function evaluate(ArchitectureSnapshot $architecture, Quote $quote): array;
}
