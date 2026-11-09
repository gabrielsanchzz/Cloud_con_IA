<?php

namespace App\Http\Controllers;

use App\Models\CarritoLinea;
use App\Models\Producto;
use App\Models\Proyecto;
use App\Services\PresupuestoService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CarritoController extends Controller
{
    public function show(Proyecto $proyecto, PresupuestoService $servicio)
    {
        $carrito = $proyecto->carrito()->firstOrCreate([]);
        $lineas = $carrito->lineas()->with('producto')->get();

        return view('carrito.show', [
            'proyecto' => $proyecto,
            'lineas' => $lineas,
            'totales' => $servicio->totales($lineas),
            'productos' => Producto::where('activo', true)->orderBy('nombre')->get(),
            'presupuestos' => $proyecto->presupuestos()->latest('emitido_en')->get(),
        ]);
    }

    public function agregar(Request $request, Proyecto $proyecto)
    {
        $datos = $request->validate([
            'producto_id' => ['required', 'integer', Rule::exists('productos', 'id')->where('activo', true)],
            'cantidad' => ['required', 'integer', 'min:1', 'max:100'],
        ], [
            'producto_id.required' => 'Tienes que elegir un producto.',
            'producto_id.exists' => 'Ese producto no existe o no está disponible.',
            'cantidad.required' => 'Tienes que poner una cantidad.',
            'cantidad.integer' => 'La cantidad tiene que ser un número entero.',
            'cantidad.min' => 'La cantidad mínima es 1.',
            'cantidad.max' => 'La cantidad máxima es 100.',
        ]);

        $carrito = $proyecto->carrito()->firstOrCreate([]);
        $linea = $carrito->lineas()->firstOrNew(['producto_id' => $datos['producto_id']]);

        $nuevaCantidad = ($linea->cantidad ?? 0) + $datos['cantidad'];
        if ($nuevaCantidad > 100) {
            return back()->withErrors(['cantidad' => 'No puedes tener más de 100 unidades del mismo producto.'])->withInput();
        }

        $linea->cantidad = $nuevaCantidad;
        $linea->save();

        return redirect()->route('carrito.show', $proyecto)->with('ok', 'Producto añadido al carrito.');
    }

    public function actualizar(Request $request, CarritoLinea $linea)
    {
        $datos = $request->validate([
            'cantidad' => ['required', 'integer', 'min:1', 'max:100'],
        ], [
            'cantidad.required' => 'Tienes que poner una cantidad.',
            'cantidad.integer' => 'La cantidad tiene que ser un número entero.',
            'cantidad.min' => 'La cantidad mínima es 1. Si no lo quieres, quítalo del carrito.',
            'cantidad.max' => 'La cantidad máxima es 100.',
        ]);

        $linea->update(['cantidad' => $datos['cantidad']]);

        return redirect()->route('carrito.show', $linea->carrito->proyecto_id)->with('ok', 'Cantidad actualizada.');
    }

    public function quitar(CarritoLinea $linea)
    {
        $proyectoId = $linea->carrito->proyecto_id;
        $linea->delete();

        return redirect()->route('carrito.show', $proyectoId)->with('ok', 'Producto quitado del carrito.');
    }
}
