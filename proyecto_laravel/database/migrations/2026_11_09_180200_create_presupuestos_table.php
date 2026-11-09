<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presupuestos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyecto_id')->constrained('proyectos')->restrictOnDelete();
            $table->string('nombre_proyecto', 100);
            $table->decimal('total_mensual', 10, 2);
            $table->decimal('total_unico', 10, 2);
            $table->timestamp('emitido_en');
            $table->timestamps();
        });

        Schema::create('presupuesto_lineas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('presupuesto_id')->constrained('presupuestos')->cascadeOnDelete();
            $table->foreignId('producto_id')->nullable()->constrained('productos')->nullOnDelete();
            $table->string('nombre_producto', 100);
            $table->string('tipo_producto', 50);
            $table->string('modalidad', 20);
            $table->decimal('precio_unitario', 10, 2);
            $table->unsignedInteger('cantidad');
            $table->json('configuracion')->nullable();
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presupuesto_lineas');
        Schema::dropIfExists('presupuestos');
    }
};
