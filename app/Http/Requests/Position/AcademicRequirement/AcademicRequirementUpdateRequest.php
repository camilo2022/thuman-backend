<?php

namespace App\Http\Requests\Position\AcademicRequirement;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AcademicRequirementUpdateRequest extends FormRequest
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
            'academic_level_id' => ['required', 'exists:subitems,id,item_id,8'],
            'academic_title' => ['required', 'string', 'max:255'],
            'formation_status_id' => ['required', 'exists:subitems,id,item_id,7']
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'exists' => 'No existe en la base de datos.',
            'string' => 'Debe ser una cadena de caracteres.',
            'max' => 'No debe exceder los :max caracteres.'
        ];
    }

    public function attributes()
    {
        return [
            'position_id' => 'Cargo',
            'academic_level_id' => 'Nivel academico',
            'academic_title' => 'Titulo academico',
            'formation_status_id' => 'Estado de la formacion'
        ];
    }
}
