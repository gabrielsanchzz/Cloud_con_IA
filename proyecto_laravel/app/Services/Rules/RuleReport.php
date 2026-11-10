<?php

declare(strict_types=1);

namespace App\Services\Rules;

use App\Enums\Severidad;

final class RuleReport
{
    /** @param list<RuleResult> $results */
    public function __construct(public readonly array $results)
    {
    }

    /** @return list<RuleResult> */
    public function blocking(): array
    {
        return array_values(array_filter($this->results, fn (RuleResult $r) => $r->severity === Severidad::Block));
    }

    /** @return list<RuleResult> */
    public function warnings(): array
    {
        return array_values(array_filter($this->results, fn (RuleResult $r) => $r->severity === Severidad::Warning));
    }

    public function isBlocked(): bool
    {
        return $this->blocking() !== [];
    }

    public function hasWarnings(): bool
    {
        return $this->warnings() !== [];
    }

    /** Un presupuesto solo puede emitirse si no hay reglas bloqueantes incumplidas. */
    public function canIssueBudget(): bool
    {
        return ! $this->isBlocked();
    }

    /** @return list<string> códigos de reglas incumplidas (útil para tests) */
    public function codes(): array
    {
        return array_map(fn (RuleResult $r) => $r->code, $this->results);
    }
}
