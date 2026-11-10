<?php

declare(strict_types=1);

namespace App\Services\Pricing;

final class LineQuote
{
    public function __construct(
        public readonly LineInput $line,
        public readonly int $monthlyCents,
        public readonly int $oneTimeCents,
    ) {
    }
}
