<?php

namespace App\Http\Requests\Resignation;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ResignationUpdateRequest extends FormRequest
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
            'person_id' => ['required', 'exists:people,id', 'exists:employees,person_id,deleted_at,NULL'],
            'resignation_type_id' => ['required', 'exists:subitems,id,item_id,46'],
            'date' => ['required', 'date', 'date_format:Y-m-d', 'after:today'],
            'reason' => ['required', 'string', 'max:5000']
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'exists' => 'No existe en la base de datos.',
            'string' => 'Debe ser una cadena de caracteres.',
            'max' => 'No debe exceder los :max caracteres.',
            'date' => 'Debe ser una fecha válida.',
            'date_format' => 'Formato inválido. Permitido :format.',
            'after' => 'Debe ser una fecha posterior a :date.'
        ];
    }

    public function attributes()
    {
        return [
            'person_id' => 'Persona',
            'resignation_type_id' => 'Tipo de renuncia',
            'date' => 'Fecha de finalización',
            'reason' => 'Motivo'
        ];
    }
}
