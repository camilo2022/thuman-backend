<?php

namespace App\Http\Requests\Authorization\Role;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RoleUpdateRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255', 'unique:roles,name,' . $this->route('roleId') . ',id'],
            'title' => ['required', 'string', 'max:255', 'unique:roles,title,' . $this->route('roleId') . ',id'],
            'description' => ['required', 'string', 'max:500'],
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'string' => 'Debe ser una cadena de caracteres.',
            'unique' => 'Ya ha sido tomado.',
            'max' => 'No debe exceder los :max caracteres.',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Llave',
            'title' => 'Nombre',
            'description' => 'Descripción',
        ];
    }
}
