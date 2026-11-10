<?php

declare(strict_types=1);

namespace App\Services\Architecture;

use App\Services\Pricing\Quote;
use App\Services\Rules\RuleReport;

final class Evaluation
{
    public function __construct(
        public readonly Quote $quote,
        public readonly RuleReport $report,
    ) {
    }
}
