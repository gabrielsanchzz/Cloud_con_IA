<?php

namespace Database\Seeders;

use App\Models\Arquitectura;
use App\Models\Proyecto;
use App\Services\Architecture\ArchitectureService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

// Añadir en DatabaseSeeder DESPUÉS de CatalogoSeeder y de crear algún proyecto:
//     $this->call(ArquitecturaSeeder::class);
class ArquitecturaSeeder extends Seeder
{
    public function run(ArchitectureService $servicio): void
    {
        $proyecto = Proyecto::first();
        if (! $proyecto || Arquitectura::where('proyecto_id', $proyecto->id)->exists()) {
            return;
        }

        // 3 ofertas disponibles de 3 tipos distintos (así la arquitectura es válida)
        $ofertas = DB::table('producto_region')
            ->join('productos', 'productos.id', '=', 'producto_region.producto_id')
            ->where('producto_region.disponible', true)
            ->where('productos.activo', true)
            ->orderBy('productos.tipo')
            ->select('producto_region.producto_id', 'producto_region.region_id', 'productos.tipo')
            ->get()
            ->unique('tipo')
            ->take(3);

        if ($ofertas->count() < 3) {
            return; // el catálogo todavía no tiene datos suficientes
        }

        $servicio->crear([
            'proyecto_id' => $proyecto->id,
            'nombre' => 'Arquitectura de ejemplo',
            'modalidad_tarifa' => 'on_demand',
            'items' => $ofertas->map(fn ($o) => [
                'producto_id' => $o->producto_id,
                'region_id' => $o->region_id,
                'cantidad' => 2,
                'configuracion' => ['vcpu' => 2, 'ram_gb' => 4],
            ])->all(),
        ]);
    }
}
