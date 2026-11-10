<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArquitecturaItem extends Model
{
    protected $table = 'arquitectura_items';

    protected $fillable = [
        'arquitectura_id',
        'producto_id',
        'region_id',
        'cantidad',
        'configuracion',
    ];

    protected $casts = [
        'cantidad' => 'integer',
        'configuracion' => 'array',
    ];

    public function arquitectura(): BelongsTo
    {
        return $this->belongsTo(Arquitectura::class);
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }
}
