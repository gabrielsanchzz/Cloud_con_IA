<?php

declare(strict_types=1);

namespace App\Services\Rules;

use App\Enums\Severidad;

final class RuleResult
{
    public function __construct(
        public readonly string $code,
        public readonly Severidad $severity,
        public readonly string $message,
        public readonly ?int $itemId = null,
    ) {
    }
}
