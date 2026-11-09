<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequirementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $type = $this->input('type');

        $operators = match ($type) {
            'number' => ['=', '!=', '>', '>=', '<', '<='],
            'text' => ['=', '!=', 'contains'],
            'boolean' => ['=', '!='],
            default => [],
        };

        return [
            'requirement_key' => [
                'required',
                'string',
                'max:80',
                Rule::unique('requirements', 'requirement_key')
                    ->where('project_id', $this->route('project')->id),
            ],
            'type' => ['required', Rule::in(['number', 'text', 'boolean'])],
            'operator' => ['required', Rule::in($operators)],
            'value' => ['required'],
            'origin' => ['required', Rule::in(['client', 'team', 'ia'])],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if (
                $this->input('type') === 'number' &&
                !is_numeric($this->input('value'))
            ) {
                $validator->errors()->add(
                    'value',
                    'El valor debe ser numérico.'
                );
            }

            if (
                $this->input('type') === 'boolean' &&
                !in_array($this->input('value'), ['0', '1'], true)
            ) {
                $validator->errors()->add(
                    'value',
                    'El valor debe ser verdadero o falso.'
                );
            }
        });
    }

    public function messages(): array
    {
        return [
            'operator.in' => 'El operador seleccionado no es válido.',
        ];
    }
}
