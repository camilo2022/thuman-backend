<?php

namespace App\Http\Requests\TerminationType\Reason;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ReasonUpdateRequest extends FormRequest
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
            'termination_type_id' => ['required', 'exists:subitems,id,item_id,47'],
            'item_id' => ['required', 'exists:items,id', 'in:48'],
            'name' => ['required', 'string', 'max:80', 'unique:subitems,name,' . $this->route('reasonId') . ',id,item_id,48'],
            'description' => ['nullable', 'string', 'max:500']
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'exists' => 'No existe en la base de datos.',
            'string' => 'Debe ser una cadena de caracteres.',
            'unique' => 'Ya ha sido tomado.',
            'max' => 'No debe exceder los :max caracteres.'
        ];
    }

    public function attributes()
    {
        return [
            'termination_type_id' => 'Tipo de terminación de contrato',
            'item_id' => 'Categorización',
            'name' => 'Nombre',
            'description' => 'Descripción'
        ];
    }
}
