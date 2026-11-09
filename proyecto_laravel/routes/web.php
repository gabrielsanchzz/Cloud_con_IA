<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EntornoController;
use App\Http\Controllers\ArchivoController;

Route::get('/', [EntornoController::class, 'index']);

Route::get('/proyectos/{proyecto}/archivos', [ArchivoController::class, 'index'])->name('archivos.index');
Route::post('/proyectos/{proyecto}/archivos', [ArchivoController::class, 'store'])->name('archivos.store');
Route::get('/archivos/{archivo}', [ArchivoController::class, 'show'])->name('archivos.show');
Route::patch('/archivos/{archivo}', [ArchivoController::class, 'update'])->name('archivos.update');
Route::delete('/archivos/{archivo}', [ArchivoController::class, 'destroy'])->name('archivos.destroy');
