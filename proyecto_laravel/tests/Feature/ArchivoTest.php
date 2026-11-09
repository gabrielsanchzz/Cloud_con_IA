<?php

namespace Tests\Feature;

use App\Models\Archivo;
use App\Models\Proyecto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ArchivoTest extends TestCase
{
    use RefreshDatabase;

    public function test_t08_sube_un_pdf_y_lo_guarda_en_storage(): void
    {
        Storage::fake('local');
        $proyecto = Proyecto::factory()->create();
        $pdf = UploadedFile::fake()->create('requisitos.pdf', 200, 'application/pdf');

        $respuesta = $this->post(route('archivos.store', $proyecto), ['archivo' => $pdf]);

        $respuesta->assertRedirect(route('archivos.index', $proyecto));
        $archivo = Archivo::first();
        $this->assertNotNull($archivo);
        $this->assertSame('requisitos.pdf', $archivo->nombre_original);
        $this->assertSame($proyecto->id, $archivo->proyecto_id);
        Storage::disk('local')->assertExists($archivo->ruta);
    }

    public function test_t08_rechaza_un_archivo_que_no_esta_permitido(): void
    {
        Storage::fake('local');
        $proyecto = Proyecto::factory()->create();
        $exe = UploadedFile::fake()->create('programa.exe', 50, 'application/x-msdownload');

        $respuesta = $this->post(route('archivos.store', $proyecto), ['archivo' => $exe]);

        $respuesta->assertSessionHasErrors('archivo');
        $this->assertDatabaseCount('archivos', 0);
        $this->assertEmpty(Storage::disk('local')->allFiles());
    }

    public function test_t08_sustituye_el_archivo_y_borra_el_anterior(): void
    {
        Storage::fake('local');
        $proyecto = Proyecto::factory()->create();

        $this->post(route('archivos.store', $proyecto), [
            'archivo' => UploadedFile::fake()->create('version1.pdf', 100, 'application/pdf'),
        ]);
        $archivo = Archivo::first();
        $rutaVieja = $archivo->ruta;

        $this->patch(route('archivos.update', $archivo), [
            'archivo' => UploadedFile::fake()->create('version2.png', 100, 'image/png'),
        ]);

        $archivo->refresh();
        $this->assertDatabaseCount('archivos', 1);
        $this->assertSame('version2.png', $archivo->nombre_original);
        Storage::disk('local')->assertMissing($rutaVieja);
        Storage::disk('local')->assertExists($archivo->ruta);
    }

    public function test_t08_subida_fallida_en_php_da_error_en_espanol(): void
    {
        Storage::fake('local');
        $proyecto = Proyecto::factory()->create();

        // Simula lo que llega cuando PHP no puede guardar el archivo temporal
        $ruta = tempnam(sys_get_temp_dir(), 'test');
        $fallido = new UploadedFile($ruta, 'requisitos.pdf', 'application/pdf', UPLOAD_ERR_CANT_WRITE, true);

        $respuesta = $this->post(route('archivos.store', $proyecto), ['archivo' => $fallido]);

        $respuesta->assertSessionHasErrors([
            'archivo' => 'El archivo no se ha podido subir. Comprueba que pesa menos de 1 MB y prueba otra vez.',
        ]);
        $this->assertDatabaseCount('archivos', 0);
    }
}
