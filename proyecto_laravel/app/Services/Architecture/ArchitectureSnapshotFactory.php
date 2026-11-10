<?php

declare(strict_types=1);

namespace App\Services\Architecture;

use App\Models\Arquitectura;
use App\Services\Pricing\LineInput;
use App\Support\Money;
use Illuminate\Support\Facades\DB;

/**
 * Convierte la arquitectura (Eloquent) en un snapshot de céntimos enteros.
 * Eager loading (sin N+1) y UNA consulta para precio/disponibilidad (tabla producto_region).
 *
 * Adaptación al catálogo del grupo:
 *  - el precio de un producto en una región está en producto_region.precio (decimal)
 *  - productos.modalidad ('mensual' | 'unico') decide si ese precio es pago mensual o único
 */
final class ArchitectureSnapshotFactory
{
    public function from(Arquitectura $arquitectura): ArchitectureSnapshot
    {
        $arquitectura->load(['proyecto', 'items.producto', 'items.region']);

        $ofertas = DB::table('producto_region')
            ->whereIn('producto_id', $arquitectura->items->pluck('producto_id'))
            ->whereIn('region_id', $arquitectura->items->pluck('region_id'))
            ->get()
            ->keyBy(fn ($o) => $o->producto_id . '-' . $o->region_id);

        $lineas = $arquitectura->items->map(function ($item) use ($ofertas) {
            $oferta = $ofertas->get($item->producto_id . '-' . $item->region_id);
            $centimos = $oferta ? Money::fromDecimal($oferta->precio) : 0;
            $esMensual = $item->producto->modalidad === 'mensual';

            return new LineInput(
                itemId: $item->id,
                productName: $item->producto->nombre,
                typeSlug: (string) $item->producto->tipo,
                regionId: $item->region_id,
                regionName: $item->region->nombre,
                quantity: (int) $item->cantidad,
                available: $oferta !== null && (bool) $oferta->disponible && (bool) $item->producto->activo,
                unitMonthlyCents: $esMensual ? $centimos : 0,
                unitSetupCents: $esMensual ? 0 : $centimos,
            );
        })->all();

        return new ArchitectureSnapshot(
            Money::fromDecimal($arquitectura->proyecto->presupuesto_maximo),
            $lineas,
        );
    }
}
