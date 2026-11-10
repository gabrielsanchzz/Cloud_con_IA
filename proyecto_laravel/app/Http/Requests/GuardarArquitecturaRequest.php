<?php

namespace App\Http\Requests;

use App\Enums\ModalidadTarifa;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GuardarArquitecturaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    // El formulario tiene 5 filas fijas (sin JavaScript): se descartan las vacías
    protected function prepareForValidation(): void
    {
        $items = collect($this->input('items', []))
            ->filter(fn ($fila) => ! empty($fila['producto_id']) || ! empty($fila['region_id']))
            ->values()
            ->all();

        $this->merge(['items' => $items]);
    }

    public function rules(): array
    {
        return [
            'proyecto_id' => ['required', 'integer', 'exists:proyectos,id'],
            'nombre' => ['required', 'string', 'max:120'],
            'modalidad_tarifa' => ['required', Rule::enum(ModalidadTarifa::class)],
            'items' => ['required', 'array', 'min:1', 'max:20'],
            'items.*.producto_id' => ['required', 'integer', 'exists:productos,id'],
            'items.*.region_id' => ['required', 'integer', 'exists:regiones,id'],
            // Límite TÉCNICO. El rango de NEGOCIO (1-100) lo aplica la regla R02
            'items.*.cantidad' => ['required', 'integer', 'min:0', 'max:10000'],
            'items.*.configuracion' => ['nullable', 'array'],
            'items.*.configuracion.vcpu' => ['nullable', 'integer', 'min:1', 'max:128'],
            'items.*.configuracion.ram_gb' => ['nullable', 'integer', 'min:1', 'max:1024'],
            'items.*.configuracion.disco_gb' => ['nullable', 'integer', 'min:1', 'max:100000'],
        ];
    }

    public function messages(): array
    {
        return [
            'proyecto_id.required' => 'Tienes que elegir un proyecto.',
            'nombre.required' => 'Tienes que poner un nombre a la arquitectura.',
            'items.required' => 'Añade al menos una línea con producto y región.',
            'items.*.producto_id.required' => 'Elige un producto en cada línea que rellenes.',
            'items.*.producto_id.exists' => 'Ese producto no existe.',
            'items.*.region_id.required' => 'Elige una región en cada línea que rellenes.',
            'items.*.region_id.exists' => 'Esa región no existe.',
            'items.*.cantidad.required' => 'Pon la cantidad de cada línea.',
            'items.*.cantidad.integer' => 'La cantidad tiene que ser un número entero.',
            'items.*.cantidad.max' => 'La cantidad es demasiado grande.',
        ];
    }
}
