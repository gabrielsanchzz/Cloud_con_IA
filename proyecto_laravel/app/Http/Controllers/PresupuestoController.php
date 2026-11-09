<?php

namespace App\Http\Controllers;

use App\Models\Presupuesto;
use App\Models\Proyecto;
use App\Services\PresupuestoService;
use DomainException;

class PresupuestoController extends Controller
{
    public function store(Proyecto $proyecto, PresupuestoService $servicio)
    {
        $carrito = $proyecto->carrito()->firstOrCreate([]);

        try {
            $presupuesto = $servicio->emitir($carrito);
        } catch (DomainException $e) {
            return redirect()->route('carrito.show', $proyecto)->withErrors(['carrito' => $e->getMessage()]);
        }

        return redirect()->route('presupuestos.show', $presupuesto)->with('ok', 'Presupuesto emitido.');
    }

    public function show(Presupuesto $presupuesto)
    {
        $presupuesto->load('lineas');

        return view('presupuestos.show', ['presupuesto' => $presupuesto]);
    }
}
