<?php

declare(strict_types=1);

namespace App\Services\Rules;

use App\Enums\Severidad;
use App\Services\Architecture\ArchitectureSnapshot;
use App\Services\Pricing\Quote;

/** R04 (AVISA): se recomienda incluir un producto de tipo "backup". No bloquea. */
final class BackupRecommendedRule implements BusinessRule
{
    public function __construct(private readonly string $backupTypeSlug = 'backup')
    {
    }

    public function code(): string
    {
        return 'R04';
    }

    public function evaluate(ArchitectureSnapshot $architecture, Quote $quote): array
    {
        if (in_array($this->backupTypeSlug, $architecture->typeSlugs(), true)) {
            return [];
        }

        return [new RuleResult(
            $this->code(),
            Severidad::Warning,
            'Recomendación: la arquitectura no incluye ningún producto de copia de seguridad. Puedes continuar, pero tus datos no tendrán respaldo.',
        )];
    }
}
