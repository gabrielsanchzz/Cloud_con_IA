
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EntornoController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RequirementController;

Route::get('/', [EntornoController::class, 'index']);

Route::resource('projects', ProjectController::class)
    ->only(['index', 'create', 'store', 'show']);

Route::get(
    '/projects/{project}/requirements/create',
    [RequirementController::class, 'create']
)->name('requirements.create');

Route::post(
    '/projects/{project}/requirements/step-two',
    [RequirementController::class, 'stepTwo']
)->name('requirements.step-two');

Route::post(
    '/projects/{project}/requirements',
    [RequirementController::class, 'store']
)->name('requirements.store');

// Editar un requisito
Route::get(
    '/projects/{project}/requirements/{requirement}/edit',
    [RequirementController::class, 'edit']
)->scopeBindings()->name('requirements.edit');

// Actualizar un requisito
Route::put(
    '/projects/{project}/requirements/{requirement}',
    [RequirementController::class, 'update']
)->scopeBindings()->name('requirements.update');

// Eliminar un requisito
Route::delete(
    '/projects/{project}/requirements/{requirement}',
    [RequirementController::class, 'destroy']
)->scopeBindings()->name('requirements.destroy');
