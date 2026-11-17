<?php

namespace App\Http\Requests\Position\RequiredExperience;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RequiredExperienceUpdateRequest extends FormRequest
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
            'position_id' => ['required', 'exists:positions,id'],
            'name' => ['required', 'string', 'max:80'],
            'duration_months' => ['required', 'numeric', 'min:1'],
            'description' => ['required', 'string', 'max:500']
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'exists' => 'No existe en la base de datos.',
            'string' => 'Debe ser una cadena de caracteres.',
            'numeric' => 'Debe ser numerico.',
            'max' => 'No debe exceder los :max caracteres.'
        ];
    }

    public function attributes()
    {
        return [
            'position_id' => 'Cargo',
            'name' => 'Nombre',
            'duration_months' => 'Experiencia en meses',
            'position_description' => 'Descripción',
        ];
    }
}
