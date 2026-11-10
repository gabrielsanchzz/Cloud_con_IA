<?php

// Añadir al final de routes/web.php:   require __DIR__ . '/arquitecturas.php';
use App\Http\Controllers\ArquitecturaController;
use Illuminate\Support\Facades\Route;

Route::get('/arquitecturas', [ArquitecturaController::class, 'index'])->name('arquitecturas.index');
Route::get('/arquitecturas/crear', [ArquitecturaController::class, 'create'])->name('arquitecturas.create');
Route::post('/arquitecturas', [ArquitecturaController::class, 'store'])->name('arquitecturas.store');
Route::get('/arquitecturas/{arquitectura}', [ArquitecturaController::class, 'show'])->name('arquitecturas.show');
Route::delete('/arquitecturas/{arquitectura}', [ArquitecturaController::class, 'destroy'])->name('arquitecturas.destroy');
Route::post('/arquitecturas/{arquitectura}/carrito', [ArquitecturaController::class, 'alCarrito'])->name('arquitecturas.carrito');
