<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
}
