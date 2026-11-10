<?php

namespace App\Models;

use App\Enums\ModalidadTarifa;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Arquitectura extends Model
{
    protected $table = 'arquitecturas';

    protected $fillable = [
        'proyecto_id',
        'nombre',
        'modalidad_tarifa',
    ];

    protected $casts = [
        'modalidad_tarifa' => ModalidadTarifa::class,
    ];

    public function proyecto(): BelongsTo
    {
        return $this->belongsTo(Proyecto::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(ArquitecturaItem::class);
    }
}
