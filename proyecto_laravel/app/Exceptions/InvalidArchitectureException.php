<?php

declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;

/** Error TÉCNICO: la arquitectura no se puede guardar (estructura inválida). */
final class InvalidArchitectureException extends RuntimeException
{
    public static function tooFewItems(int $found, int $min): self
    {
        return new self("Una arquitectura necesita al menos {$min} elementos (tiene {$found}).");
    }

    public static function tooFewTypes(int $found, int $min): self
    {
        return new self("Una arquitectura necesita al menos {$min} tipos de producto distintos (tiene {$found}).");
    }

    public static function duplicatedItem(string $product, string $region): self
    {
        return new self("El producto «{$product}» aparece repetido en la región {$region}. Suma las cantidades en una sola línea.");
    }
}
