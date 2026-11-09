<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubirArchivoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'archivo' => ['required', 'file', 'mimes:pdf,png,jpg,jpeg', 'max:1024'],
        ];
    }

    public function messages(): array
    {
        return [
            'archivo.required' => 'Tienes que elegir un archivo.',
            'archivo.uploaded' => 'El archivo no se ha podido subir. Comprueba que pesa menos de 1 MB y prueba otra vez.',
            'archivo.file' => 'El archivo no se ha subido bien, prueba otra vez.',
            'archivo.mimes' => 'Solo se permiten archivos PDF, PNG o JPG.',
            'archivo.max' => 'El archivo no puede pesar más de 1 MB.',
        ];
    }
}
