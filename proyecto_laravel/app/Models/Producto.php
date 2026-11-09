<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'proveedor_id',
        'tipo_producto_id',
        'nombre',
        'descripcion',
        'tipo',
        'precio',
        'modalidad',
        'activo',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'activo' => 'boolean',
    ];

    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function tipoProducto(): BelongsTo
    {
        return $this->belongsTo(TipoProducto::class, 'tipo_producto_id');
    }

    // N:M con datos en la tabla pivote: disponible y precio en esa región
    public function regiones(): BelongsToMany
    {
        return $this->belongsToMany(Region::class, 'producto_region')
            ->withPivot('disponible', 'precio')
            ->withTimestamps();
    }

    public function scopeActivos(Builder $query): Builder
    {
        return $query->where('activo', true);
    }

    // Productos que se pueden contratar en una región concreta
    public function scopeDisponiblesEn(Builder $query, int $regionId): Builder
    {
        return $query->whereHas('regiones', function (Builder $q) use ($regionId) {
            $q->where('regiones.id', $regionId)->where('producto_region.disponible', true);
        });
    }
}
