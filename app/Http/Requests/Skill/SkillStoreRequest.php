<?php

namespace App\Http\Requests\Skill;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SkillStoreRequest extends FormRequest
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
            'skill_type_id' => ['required', 'exists:subitems,id,item_id,6'],
            'item_id' => ['required', 'exists:items,id', 'in:29'],
            'name' => ['required', 'string', 'max:80', 'regex:/^\S(?:.*\S)?$/', 'unique:subitems,name,NULL,id,item_id,29'],
            'description' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'exists' => 'No existe en la base de datos.',
            'string' => 'Debe ser una cadena de caracteres.',
            'unique' => 'Ya ha sido tomado.',
            'regex' => 'No debe tener espacios al inicio ni al final.',
            'max' => 'No debe exceder los :max caracteres.',
        ];
    }

    public function attributes()
    {
        return [
            'skill_type_id' => 'Tipo de habilidad',
            'item_id' => 'Categorización',
            'name' => 'Nombre',
            'description' => 'Descripción',
        ];
    }
}
