<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Proyecto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'presupuesto_maximo',
    ];

    protected $casts = [
        'presupuesto_maximo' => 'decimal:2',
    ];

    public function archivos(): HasMany
    {
        return $this->hasMany(Archivo::class);
    }

    public function carrito(): HasOne
    {
        return $this->hasOne(Carrito::class);
    }

    public function presupuestos(): HasMany
    {
        return $this->hasMany(Presupuesto::class);
    }
}
