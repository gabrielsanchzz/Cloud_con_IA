<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EntornoController extends Controller
{
public function index()
{
    return view('entorno', [
        'app'     => config('app.name'),
        'equipo'  => 'Cloud_con_IA (Gabriel, Joselu, Eliezer, Javi)',
        'laravel' => app()->version(),
        'php'     => PHP_VERSION,
    ]);
  }
}
