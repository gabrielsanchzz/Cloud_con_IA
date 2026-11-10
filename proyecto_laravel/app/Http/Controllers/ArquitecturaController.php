<?php

namespace App\Http\Controllers;

use App\Enums\ModalidadTarifa;
use App\Exceptions\InvalidArchitectureException;
use App\Http\Requests\GuardarArquitecturaRequest;
use App\Models\Arquitectura;
use App\Models\Producto;
use App\Models\Proyecto;
use App\Models\Region;
use App\Services\Architecture\ArchitectureService;
use Illuminate\Http\Request;

class ArquitecturaController extends Controller
{
    public function __construct(private readonly ArchitectureService $servicio)
    {
    }

    public function index()
    {
        return view('arquitecturas.index', [
            'arquitecturas' => Arquitectura::with('proyecto')->withCount('items')->latest()->paginate(10),
        ]);
    }

    public function create(Request $request)
    {
        return view('arquitecturas.create', [
            'proyectos' => Proyecto::orderBy('nombre')->get(),
            'productos' => Producto::where('activo', true)->orderBy('nombre')->get(),
            'regiones' => Region::orderBy('nombre')->get(),
            'modalidades' => ModalidadTarifa::cases(),
            'proyectoElegido' => $request->query('proyecto_id'),
            'filas' => 5,
        ]);
    }

    public function store(GuardarArquitecturaRequest $request)
    {
        try {
            $arquitectura = $this->servicio->crear($request->validated());
        } catch (InvalidArchitectureException $e) {
            // Error técnico: no se ha guardado nada (rollback) y se conservan los datos escritos
            return back()->withInput()->withErrors(['arquitectura' => $e->getMessage()]);
        }

        return redirect()->route('arquitecturas.show', $arquitectura)->with('ok', 'Arquitectura guardada.');
    }

    public function show(Request $request, Arquitectura $arquitectura)
    {
        // GET ?modalidad=...: comparar tarifas sin guardar nada. El cálculo es siempre del servidor.
        $modalidad = ModalidadTarifa::tryFrom((string) $request->query('modalidad')) ?? $arquitectura->modalidad_tarifa;
        $evaluacion = $this->servicio->evaluar($arquitectura, $modalidad);

        return view('arquitecturas.show', [
            'arquitectura' => $arquitectura->load('proyecto', 'items.producto', 'items.region'),
            'quote' => $evaluacion->quote,
            'informe' => $evaluacion->report,
            'modalidad' => $modalidad,
            'modalidades' => ModalidadTarifa::cases(),
        ]);
    }

    public function destroy(Arquitectura $arquitectura)
    {
        $arquitectura->delete(); // los items se borran en cascada

        return redirect()->route('arquitecturas.index')->with('ok', 'Arquitectura eliminada.');
    }

    /**
     * Pasa los productos de la arquitectura al carrito del proyecto (módulo de Javier).
     * Se vuelven a evaluar las reglas en el servidor: si hay un bloqueo no se pasa nada.
     */
    public function alCarrito(Arquitectura $arquitectura)
    {
        $informe = $this->servicio->evaluar($arquitectura)->report;

        if (! $informe->canIssueBudget()) {
            $motivos = array_map(fn ($r) => $r->message, $informe->blocking());

            return back()->withErrors($motivos);
        }

        $carrito = $arquitectura->proyecto->carrito()->firstOrCreate([]);

        foreach ($arquitectura->items as $item) {
            $linea = $carrito->lineas()->firstOrNew(['producto_id' => $item->producto_id]);
            $linea->cantidad = min(100, ($linea->cantidad ?? 0) + $item->cantidad);
            $linea->configuracion = $item->configuracion;
            $linea->save();
        }

        return redirect()->route('carrito.show', $arquitectura->proyecto)
            ->with('ok', 'Arquitectura añadida al carrito.');
    }
}
