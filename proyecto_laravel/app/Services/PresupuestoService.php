<?php

namespace App\Services;

use App\Models\Carrito;
use App\Models\Presupuesto;
use DomainException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PresupuestoService
{
    public function subtotal(float $precio, int $cantidad): float
    {
        return round($precio * $cantidad, 2);
    }

    public function totales(Collection $lineas): array
    {
        $mensual = 0;
        $unico = 0;

        foreach ($lineas as $linea) {
            $subtotal = $this->subtotal((float) $linea->producto->precio, $linea->cantidad);

            if ($linea->producto->modalidad === 'mensual') {
                $mensual += $subtotal;
            } else {
                $unico += $subtotal;
            }
        }

        return [
            'mensual' => round($mensual, 2),
            'unico' => round($unico, 2),
        ];
    }

    public function emitir(Carrito $carrito): Presupuesto
    {
        $lineas = $carrito->lineas()->with('producto')->get();

        if ($lineas->isEmpty()) {
            throw new DomainException('El carrito está vacío, no se puede emitir el presupuesto.');
        }

        foreach ($lineas as $linea) {
            if (! $linea->producto->activo) {
                throw new DomainException('El producto ' . $linea->producto->nombre . ' ya no está disponible.');
            }
        }

        return DB::transaction(function () use ($carrito, $lineas) {
            $totales = $this->totales($lineas);
            $proyecto = $carrito->proyecto;

            $presupuesto = $proyecto->presupuestos()->create([
                'nombre_proyecto' => $proyecto->nombre,
                'total_mensual' => $totales['mensual'],
                'total_unico' => $totales['unico'],
                'emitido_en' => now(),
            ]);

            // Se copian los datos del producto tal y como están ahora, para que el
            // presupuesto no cambie aunque luego se modifique el catálogo
            foreach ($lineas as $linea) {
                $presupuesto->lineas()->create([
                    'producto_id' => $linea->producto->id,
                    'nombre_producto' => $linea->producto->nombre,
                    'tipo_producto' => $linea->producto->tipo,
                    'modalidad' => $linea->producto->modalidad,
                    'precio_unitario' => $linea->producto->precio,
                    'cantidad' => $linea->cantidad,
                    'configuracion' => $linea->configuracion,
                    'subtotal' => $this->subtotal((float) $linea->producto->precio, $linea->cantidad),
                ]);
            }

            $carrito->lineas()->delete();

            return $presupuesto;
        });
    }
}
