<?php

namespace App\Http\Requests\Position;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class PositionStoreRequest extends FormRequest
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
            'area_id' => ['required', 'exists:areas,id'],
            'name' => ['required', 'string', 'max:80', 'unique:positions,name'],
            'profile_category_id' => ['required', 'exists:subitems,id,item_id,13'],
            'description' => ['required', 'string', 'max:2000'],
            'settings' => ['required', 'array'],
            'settings.polygraph' => ['required', 'boolean'],
            'settings.video_interview' => ['required', 'boolean']
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'string' => 'Debe ser una cadena de caracteres.',
            'unique' => 'Ya ha sido tomado.',
            'max' => 'No debe exceder los :max caracteres.',
            'exists' => 'No existe en la base de datos.',
            'array' => 'Debe ser una lista de datos.',
            'boolean' => 'Deber ser requerido o no requerido.'
        ];
    }

    public function attributes()
    {
        return [
            'area_id' => 'Area',
            'name' => 'Nombre',
            'profile_category_id' => 'Categoria del cargo',
            'description' => 'Descripción',
            'settings' => 'Configuración',
            'settings.polygraph' => 'Poligrafo',
            'settings.video_interview' => 'Video entrevista',
        ];
    }
}
