<?php

namespace App\Http\Requests\Navigation\Module;

use App\Models\Module;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ModuleStoreRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:80', 'unique:modules,name'],
            'type' => ['required', 'in:' . implode(',', Module::types())],
            'route' => ['required', 'string', 'max:255', 'unique:modules,route']
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'string' => 'Debe ser una cadena de caracteres.',
            'unique' => 'Ya ha sido tomado.',
            'max' => 'No debe exceder los :max caracteres.',
            'in' => 'Valor inválido. Permitidos: :values.'
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Nombre',
            'type' => 'Tipo',
            'route' => 'Ruta',
        ];
    }
}
