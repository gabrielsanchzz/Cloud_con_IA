<?php

declare(strict_types=1);

namespace App\Services\Pricing;

/** Línea de arquitectura ya resuelta contra el catálogo (sin depender de Eloquent). */
final class LineInput
{
    public function __construct(
        public readonly int $itemId,
        public readonly string $productName,
        public readonly string $typeSlug,
        public readonly int $regionId,
        public readonly string $regionName,
        public readonly int $quantity,
        public readonly bool $available,
        public readonly int $unitMonthlyCents,
        public readonly int $unitSetupCents,
    ) {
    }
}
