<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Presupuesto extends Model
{
    protected $fillable = [
        'nombre_proyecto',
        'total_mensual',
        'total_unico',
        'emitido_en',
    ];

    protected $casts = [
        'total_mensual' => 'decimal:2',
        'total_unico' => 'decimal:2',
        'emitido_en' => 'datetime',
    ];

    public function proyecto(): BelongsTo
    {
        return $this->belongsTo(Proyecto::class);
    }

    public function lineas(): HasMany
    {
        return $this->hasMany(PresupuestoLinea::class);
    }
}
