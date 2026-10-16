<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EntornoController;

Route::get('/', [EntornoController::class, 'index']);
