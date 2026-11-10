<?php

declare(strict_types=1);

namespace App\Services\Architecture;

use App\Enums\ModalidadTarifa;
use App\Models\Arquitectura;
use App\Services\Pricing\PriceCalculator;
use App\Services\Rules\RuleEngine;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

final class ArchitectureService
{
    public function __construct(
        private readonly ArchitectureSnapshotFactory $snapshots,
        private readonly ArchitectureValidator $validator,
        private readonly PriceCalculator $calculator,
        private readonly RuleEngine $rules,
    ) {
    }

    /**
     * Guarda cabecera + líneas en UNA transacción. Si la estructura es inválida
     * (error técnico) se lanza InvalidArchitectureException y se revierte todo.
     */
    public function crear(array $datos): Arquitectura
    {
        return DB::transaction(function () use ($datos) {
            $arquitectura = Arquitectura::create(Arr::only($datos, ['proyecto_id', 'nombre', 'modalidad_tarifa']));

            foreach ($datos['items'] as $fila) {
                $arquitectura->items()->create(Arr::only($fila, ['producto_id', 'region_id', 'cantidad', 'configuracion']));
            }

            $this->validator->validate($this->snapshots->from($arquitectura));

            return $arquitectura;
        });
    }

    /** Recalcula precio y reglas con datos del servidor. */
    public function evaluar(Arquitectura $arquitectura, ?ModalidadTarifa $modalidad = null): Evaluation
    {
        $snapshot = $this->snapshots->from($arquitectura);
        $quote = $this->calculator->quote($snapshot->lines, $modalidad ?? $arquitectura->modalidad_tarifa);

        return new Evaluation($quote, $this->rules->evaluate($snapshot, $quote));
    }
}
