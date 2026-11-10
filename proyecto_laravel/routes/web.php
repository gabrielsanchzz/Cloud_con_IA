<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EntornoController;
use App\Http\Controllers\ArchivoController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\PresupuestoController;
use App\Http\Controllers\CatalogoController;

Route::get('/', [EntornoController::class, 'index']);
Route::get('/', [EntornoController::class, 'index']);

Route::get('/proyectos/{proyecto}/archivos', [ArchivoController::class, 'index'])->name('archivos.index');
Route::post('/proyectos/{proyecto}/archivos', [ArchivoController::class, 'store'])->name('archivos.store');
Route::get('/archivos/{archivo}', [ArchivoController::class, 'show'])->name('archivos.show');
Route::patch('/archivos/{archivo}', [ArchivoController::class, 'update'])->name('archivos.update');
Route::delete('/archivos/{archivo}', [ArchivoController::class, 'destroy'])->name('archivos.destroy');

Route::get('/proyectos/{proyecto}/carrito', [CarritoController::class, 'show'])->name('carrito.show');
Route::post('/proyectos/{proyecto}/carrito', [CarritoController::class, 'agregar'])->name('carrito.agregar');
Route::patch('/carrito/lineas/{linea}', [CarritoController::class, 'actualizar'])->name('carrito.actualizar');
Route::delete('/carrito/lineas/{linea}', [CarritoController::class, 'quitar'])->name('carrito.quitar');

Route::post('/proyectos/{proyecto}/presupuestos', [PresupuestoController::class, 'store'])->name('presupuestos.store');
Route::get('/presupuestos/{presupuesto}', [PresupuestoController::class, 'show'])->name('presupuestos.show');

Route::get('/catalogo', [CatalogoController::class, 'index'])->name('catalogo.index');
Route::get('/catalogo/{producto}', [CatalogoController::class, 'show'])->name('catalogo.show');