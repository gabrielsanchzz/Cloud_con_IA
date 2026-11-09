<?php

namespace Tests\Feature;

use App\Models\Producto;
use App\Models\Proyecto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CarritoTest extends TestCase
{
    use RefreshDatabase;

    public function test_el_carrito_se_guarda_en_bd_y_se_conserva_al_recargar(): void
    {
        $proyecto = Proyecto::factory()->create();
        $producto = Producto::factory()->create(['nombre' => 'Servidor web']);

        $this->post(route('carrito.agregar', $proyecto), ['producto_id' => $producto->id, 'cantidad' => 2]);

        $this->assertDatabaseHas('carrito_lineas', ['producto_id' => $producto->id, 'cantidad' => 2]);
        $this->get(route('carrito.show', $proyecto))->assertSee('Servidor web');
    }

    public function test_se_puede_cambiar_la_cantidad_y_quitar_una_linea(): void
    {
        $proyecto = Proyecto::factory()->create();
        $producto = Producto::factory()->create();
        $this->post(route('carrito.agregar', $proyecto), ['producto_id' => $producto->id, 'cantidad' => 1]);
        $linea = $proyecto->carrito->lineas()->first();

        $this->patch(route('carrito.actualizar', $linea), ['cantidad' => 5]);
        $this->assertSame(5, $linea->fresh()->cantidad);

        $this->delete(route('carrito.quitar', $linea));
        $this->assertDatabaseCount('carrito_lineas', 0);
    }

    public function test_rechaza_una_cantidad_fuera_de_rango(): void
    {
        $proyecto = Proyecto::factory()->create();
        $producto = Producto::factory()->create();

        $respuesta = $this->post(route('carrito.agregar', $proyecto), ['producto_id' => $producto->id, 'cantidad' => 0]);

        $respuesta->assertSessionHasErrors('cantidad');
        $this->assertDatabaseCount('carrito_lineas', 0);
    }

    public function test_un_carrito_vacio_no_emite_presupuesto(): void
    {
        $proyecto = Proyecto::factory()->create();

        $respuesta = $this->post(route('presupuestos.store', $proyecto));

        $respuesta->assertSessionHasErrors('carrito');
        $this->assertDatabaseCount('presupuestos', 0);
    }
}
