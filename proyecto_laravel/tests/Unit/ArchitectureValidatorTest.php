<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Exceptions\InvalidArchitectureException;
use App\Services\Architecture\ArchitectureSnapshot;
use App\Services\Architecture\ArchitectureValidator;
use PHPUnit\Framework\TestCase;
use Tests\Support\Lines;

/** Extra: arquitectura válida (≥3 elementos y ≥2 tipos) y rechazos por error técnico. */
class ArchitectureValidatorTest extends TestCase
{
    public function test_arquitectura_valida_no_lanza_excepcion(): void
    {
        (new ArchitectureValidator())->validate(new ArchitectureSnapshot(null, Lines::tienda()));
        $this->addToAssertionCount(1);
    }

    public function test_menos_de_tres_elementos_se_rechaza(): void
    {
        $this->expectException(InvalidArchitectureException::class);
        $this->expectExceptionMessage('al menos 3 elementos');
        (new ArchitectureValidator())->validate(new ArchitectureSnapshot(null, array_slice(Lines::tienda(), 0, 2)));
    }

    public function test_un_solo_tipo_se_rechaza(): void
    {
        $lines = [
            Lines::make(['itemId' => 1, 'productName' => 'VM A']),
            Lines::make(['itemId' => 2, 'productName' => 'VM B']),
            Lines::make(['itemId' => 3, 'productName' => 'VM C']),
        ];
        $this->expectException(InvalidArchitectureException::class);
        $this->expectExceptionMessage('2 tipos');
        (new ArchitectureValidator())->validate(new ArchitectureSnapshot(null, $lines));
    }

    public function test_producto_repetido_en_la_misma_region_se_rechaza(): void
    {
        $lines = Lines::tienda();
        $lines[] = Lines::make(['itemId' => 9, 'productName' => 'VM Estándar']);
        $this->expectException(InvalidArchitectureException::class);
        (new ArchitectureValidator())->validate(new ArchitectureSnapshot(null, $lines));
    }
}
