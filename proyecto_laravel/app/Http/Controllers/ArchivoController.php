<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubirArchivoRequest;
use App\Models\Archivo;
use App\Models\Proyecto;
use Illuminate\Support\Facades\Storage;

class ArchivoController extends Controller
{
    public function index(Proyecto $proyecto)
    {
        $archivos = $proyecto->archivos()->latest()->get();

        return view('archivos.index', [
            'proyecto' => $proyecto,
            'archivos' => $archivos,
        ]);
    }

    public function store(SubirArchivoRequest $request, Proyecto $proyecto)
    {
        $fichero = $request->file('archivo');
        $ruta = $fichero->store('proyectos/' . $proyecto->id, 'local');

        try {
            $proyecto->archivos()->create([
                'nombre_original' => $fichero->getClientOriginalName(),
                'ruta' => $ruta,
                'tipo_mime' => $fichero->getMimeType(),
                'tamano' => $fichero->getSize(),
            ]);
        } catch (\Throwable $e) {
            // Si falla la BD el archivo ya está en el disco, y eso no lo deshace nadie solo
            Storage::disk('local')->delete($ruta);
            throw $e;
        }

        return redirect()
            ->route('archivos.index', $proyecto)
            ->with('ok', 'Archivo subido correctamente.');
    }

    public function show(Archivo $archivo)
    {
        if (! Storage::disk('local')->exists($archivo->ruta)) {
            abort(404);
        }

        return Storage::disk('local')->response($archivo->ruta, $archivo->nombre_original);
    }

    public function update(SubirArchivoRequest $request, Archivo $archivo)
    {
        $fichero = $request->file('archivo');
        $rutaVieja = $archivo->ruta;
        $rutaNueva = $fichero->store('proyectos/' . $archivo->proyecto_id, 'local');

        try {
            $archivo->update([
                'nombre_original' => $fichero->getClientOriginalName(),
                'ruta' => $rutaNueva,
                'tipo_mime' => $fichero->getMimeType(),
                'tamano' => $fichero->getSize(),
            ]);
        } catch (\Throwable $e) {
            Storage::disk('local')->delete($rutaNueva);
            throw $e;
        }

        // El viejo se borra al final, cuando ya sabemos que el nuevo está guardado
        Storage::disk('local')->delete($rutaVieja);

        return redirect()
            ->route('archivos.index', $archivo->proyecto_id)
            ->with('ok', 'Archivo sustituido correctamente.');
    }

    public function destroy(Archivo $archivo)
    {
        $proyectoId = $archivo->proyecto_id;
        $ruta = $archivo->ruta;

        $archivo->delete();
        Storage::disk('local')->delete($ruta);

        return redirect()
            ->route('archivos.index', $proyectoId)
            ->with('ok', 'Archivo eliminado.');
    }
}
