<?php

namespace App\Http\Requests\RiskManager;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RiskManagerUpdateRequest extends FormRequest
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
            'item_id' => ['required', 'exists:items,id', 'in:24'],
            'name' => ['required', 'string', 'max:80', 'unique:subitems,name,' . $this->route('riskManagerId') . ',id,item_id,24'],
            'description' => ['required', 'string', 'max:500'],
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'exists' => 'No existe en la base de datos.',
            'string' => 'Debe ser una cadena de caracteres.',
            'unique' => 'Ya ha sido tomado.',
            'max' => 'No debe exceder los :max caracteres.',
        ];
    }

    public function attributes()
    {
        return [
            'item_id' => 'Categorización',
            'name' => 'Nombre',
            'description' => 'Descripción',
        ];
    }
}
