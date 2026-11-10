<?php

declare(strict_types=1);

namespace App\Services\Architecture;

use App\Exceptions\InvalidArchitectureException;

/** Validación ESTRUCTURAL (error técnico): mínimo 3 elementos, 2 tipos y sin duplicados. */
final class ArchitectureValidator
{
    public const MIN_ITEMS = 3;
    public const MIN_TYPES = 2;

    public function validate(ArchitectureSnapshot $architecture): void
    {
        $count = count($architecture->lines);
        if ($count < self::MIN_ITEMS) {
            throw InvalidArchitectureException::tooFewItems($count, self::MIN_ITEMS);
        }

        $types = count($architecture->typeSlugs());
        if ($types < self::MIN_TYPES) {
            throw InvalidArchitectureException::tooFewTypes($types, self::MIN_TYPES);
        }

        $seen = [];
        foreach ($architecture->lines as $line) {
            $key = $line->productName . '|' . $line->regionId;
            if (isset($seen[$key])) {
                throw InvalidArchitectureException::duplicatedItem($line->productName, $line->regionName);
            }
            $seen[$key] = true;
        }
    }
}
