<?php

namespace Tests\Feature;

use App\Models\Presupuesto;
use App\Models\PresupuestoLinea;
use App\Models\Producto;
use App\Models\Proyecto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use RuntimeException;
use Tests\TestCase;

class PresupuestoTest extends TestCase
{
    use RefreshDatabase;

    public function test_t09_el_presupuesto_no_cambia_si_se_modifica_el_catalogo(): void
    {
        $proyecto = Proyecto::factory()->create();
        $producto = Producto::factory()->create([
            'nombre' => 'Servidor web',
            'precio' => 20.00,
            'modalidad' => 'mensual',
        ]);
        $this->post(route('carrito.agregar', $proyecto), ['producto_id' => $producto->id, 'cantidad' => 3]);
        $this->post(route('presupuestos.store', $proyecto));
        $presupuesto = Presupuesto::first();

        // Se cambia el catálogo después de emitir
        $producto->update(['nombre' => 'Servidor web PRO', 'precio' => 35.50]);

        $presupuesto->refresh();
        $linea = $presupuesto->lineas()->first();
        $this->assertSame('Servidor web', $linea->nombre_producto);
        $this->assertSame('20.00', $linea->precio_unitario);
        $this->assertSame('60.00', $linea->subtotal);
        $this->assertSame('60.00', $presupuesto->total_mensual);
    }

    public function test_t10_si_falla_la_emision_no_queda_nada_a_medias(): void
    {
        $proyecto = Proyecto::factory()->create();
        $productoA = Producto::factory()->create();
        $productoB = Producto::factory()->create();
        $this->post(route('carrito.agregar', $proyecto), ['producto_id' => $productoA->id, 'cantidad' => 1]);
        $this->post(route('carrito.agregar', $proyecto), ['producto_id' => $productoB->id, 'cantidad' => 2]);

        // Fallo provocado: la segunda línea del presupuesto no se puede guardar
        $guardadas = 0;
        PresupuestoLinea::creating(function () use (&$guardadas) {
            $guardadas++;
            if ($guardadas === 2) {
                throw new RuntimeException('Fallo provocado al guardar la segunda línea');
            }
        });

        $this->withoutExceptionHandling();
        try {
            $this->post(route('presupuestos.store', $proyecto));
            $this->fail('Tendría que haber saltado el error');
        } catch (RuntimeException $e) {
            $this->assertSame('Fallo provocado al guardar la segunda línea', $e->getMessage());
        }

        $this->assertDatabaseCount('presupuestos', 0);
        $this->assertDatabaseCount('presupuesto_lineas', 0);
        $this->assertDatabaseCount('carrito_lineas', 2);
    }
}
