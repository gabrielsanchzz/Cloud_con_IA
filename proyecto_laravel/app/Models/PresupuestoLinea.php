<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PresupuestoLinea extends Model
{
    protected $fillable = [
        'producto_id',
        'nombre_producto',
        'tipo_producto',
        'modalidad',
        'precio_unitario',
        'cantidad',
        'configuracion',
        'subtotal',
    ];

    protected $casts = [
        'precio_unitario' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'configuracion' => 'array',
    ];

    public function presupuesto(): BelongsTo
    {
        return $this->belongsTo(Presupuesto::class);
    }
}
