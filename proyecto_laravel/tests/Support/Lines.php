<?php

declare(strict_types=1);

namespace Tests\Support;

use App\Services\Pricing\LineInput;

/** Constructores de datos de prueba (sin base de datos). */
final class Lines
{
    public static function make(array $o = []): LineInput
    {
        return new LineInput(...array_merge([
            'itemId' => 1,
            'productName' => 'VM Estándar',
            'typeSlug' => 'compute',
            'regionId' => 1,
            'regionName' => 'Irlanda (eu-west)',
            'quantity' => 1,
            'available' => true,
            'unitMonthlyCents' => 1000,
            'unitSetupCents' => 0,
        ], $o));
    }

    /** Arquitectura del caso de uso: 4 líneas, 4 tipos, todo en Irlanda (sin backup). */
    public static function tienda(): array
    {
        return [
            self::make(['itemId' => 1, 'productName' => 'VM Estándar', 'typeSlug' => 'compute', 'quantity' => 2, 'unitMonthlyCents' => 3333, 'unitSetupCents' => 0]),
            self::make(['itemId' => 2, 'productName' => 'Base de datos gestionada', 'typeSlug' => 'database', 'quantity' => 1, 'unitMonthlyCents' => 12050, 'unitSetupCents' => 2500]),
            self::make(['itemId' => 3, 'productName' => 'Almacenamiento de objetos', 'typeSlug' => 'storage', 'quantity' => 3, 'unitMonthlyCents' => 999, 'unitSetupCents' => 0]),
            self::make(['itemId' => 4, 'productName' => 'Balanceador de carga', 'typeSlug' => 'network', 'quantity' => 1, 'unitMonthlyCents' => 1800, 'unitSetupCents' => 1000]),
        ];
    }
}
