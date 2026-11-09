<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarritoLinea extends Model
{
    protected $fillable = [
        'producto_id',
        'cantidad',
        'configuracion',
    ];

    protected $casts = [
        'configuracion' => 'array',
    ];

    public function carrito(): BelongsTo
    {
        return $this->belongsTo(Carrito::class);
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }
}
