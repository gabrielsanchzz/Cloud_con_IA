<?php

declare(strict_types=1);

namespace App\Services\Pricing;

use App\Enums\ModalidadTarifa;

final class Quote
{
    /** @param list<LineQuote> $lines */
    public function __construct(
        public readonly ModalidadTarifa $model,
        public readonly array $lines,
        public readonly int $monthlyTotalCents,
        public readonly int $oneTimeTotalCents,
    ) {
    }

    /** Coste del primer año = 12 pagos mensuales + pagos únicos. */
    public function firstYearTotalCents(): int
    {
        return $this->monthlyTotalCents * 12 + $this->oneTimeTotalCents;
    }
}
