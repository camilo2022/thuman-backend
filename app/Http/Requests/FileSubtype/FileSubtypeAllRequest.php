<?php

namespace App\Http\Requests\FileSubtype;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class FileSubtypeAllRequest extends FormRequest
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
            'search' => ['nullable', 'string', 'max:100'],
            'perPage' => ['nullable', 'numeric'],
            'file_type_id' => ['nullable', 'exists:subitems,id,item_id,16']
        ];
    }

    public function messages()
    {
        return [
            'string' => 'Debe ser una cadena de caracteres.',
            'numeric' => 'Debe ser un valor numérico.',
            'exists' => 'No existe en la base de datos.'
        ];
    }

    public function attributes()
    {
        return [
            'search' => 'Término de búsqueda',
            'perPage' => 'Número de registros por página',
            'file_type_id' => 'Tipo de archivo'
        ];
    }
}
