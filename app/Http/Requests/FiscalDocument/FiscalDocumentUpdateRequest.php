<?php

namespace App\Http\Requests\FiscalDocument;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class FiscalDocumentUpdateRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Error de validación.',
            'attributes' => $this->attributes(),
            'errors' => $validator->errors()
        ], 422));
    }

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'item_id' => ['required', 'exists:items,id', 'in:31'],
            'name' => ['required', 'string', 'max:80', 'unique:subitems,name,' . $this->route('fiscalDocumentId') . ',id,item_id,31'],
            'description' => ['required', 'string', 'max:500'],
            'settings' => ['required', 'array'],
            'settings.abreviature' => ['required', 'string', 'regex:/^(?:[A-ZÁÉÍÓÚÜ]{1,10})(?:\.[A-ZÁÉÍÓÚÜ]{1,10})*\.?$/']
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'exists' => 'No existe en la base de datos.',
            'string' => 'Debe ser una cadena de caracteres.',
            'unique' => 'Ya ha sido tomado.',
            'array' => 'Debe ser una lista de datos.',
            'max' => 'No debe exceder los :max caracteres.',
            'regex' => 'Formato de abreviatura inválido.'
        ];
    }

    public function attributes()
    {
        return [
            'item_id' => 'Categorización',
            'name' => 'Nombre',
            'description' => 'Descripción',
            'settings' => 'Configuración',
            'settings.abreviature' => 'Abreviatura'
        ];
    }
}
