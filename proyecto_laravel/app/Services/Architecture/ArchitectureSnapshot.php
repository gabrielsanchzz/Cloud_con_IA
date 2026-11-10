<?php

declare(strict_types=1);

namespace App\Services\Architecture;

use App\Services\Pricing\LineInput;

/** Foto inmutable de una arquitectura lista para validar, calcular y evaluar reglas. */
final class ArchitectureSnapshot
{
    /** @param list<LineInput> $lines */
    public function __construct(
        public readonly ?int $monthlyBudgetCents,
        public readonly array $lines,
    ) {
    }

    /** @return list<string> */
    public function typeSlugs(): array
    {
        return array_values(array_unique(array_map(fn (LineInput $l) => $l->typeSlug, $this->lines)));
    }

    /** @return list<string> */
    public function regionNames(): array
    {
        return array_values(array_unique(array_map(fn (LineInput $l) => $l->regionName, $this->lines)));
    }
}
