<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// IMPORTANTE: necesita que ya existan las tablas `proyectos`, `productos` y `regiones`
// (la de regiones la crea la migración del catálogo). Esta migración debe tener fecha POSTERIOR.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('arquitecturas', function (Blueprint $table) {
            $table->id();
            // Si se borra el proyecto se borran sus arquitecturas
            $table->foreignId('proyecto_id')->constrained('proyectos')->cascadeOnDelete();
            $table->string('nombre', 120);
            $table->string('modalidad_tarifa', 20)->default('on_demand');
            $table->timestamps();

            $table->index(['proyecto_id', 'nombre']);
        });

        Schema::create('arquitectura_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('arquitectura_id')->constrained('arquitecturas')->cascadeOnDelete();
            // No se puede borrar un producto o una región que esté en uso
            $table->foreignId('producto_id')->constrained('productos')->restrictOnDelete();
            $table->foreignId('region_id')->constrained('regiones')->restrictOnDelete();
            $table->unsignedSmallInteger('cantidad');
            $table->json('configuracion')->nullable();
            $table->timestamps();

            $table->index(['arquitectura_id', 'producto_id', 'region_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('arquitectura_items');
        Schema::dropIfExists('arquitecturas');
    }
};
