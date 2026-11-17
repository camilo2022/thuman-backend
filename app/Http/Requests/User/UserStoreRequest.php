<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserStoreRequest extends FormRequest
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
            'person_id' => ['required', 'exists:people,id', 'unique:users,person_id'],
            'password' => [
                'required', 'string', 'min:8', 'max:75', 'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&.,;:])[A-Za-z\d@$!%*#?&.,;:]{8,}$/'
            ],
            'password_confirmation' => ['required', 'string']
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'string' => 'Debe ser una cadena de caracteres.',
            'unique' => 'Ya ha sido tomado.',
            'max' => 'No debe exceder los :max caracteres.',
            'min' => 'Debe tener al menos :min caracteres.',
            'exists' => 'No existe en la base de datos.',
            'confirmed' => 'No coincide con la confirmación.',
            'regex' => 'Debe contener al menos una letra mayúscula, una letra minúscula, un número y un carácter especial.',
        ];
    }

    public function attributes()
    {
        return [
            'person_id' => 'Persona',
            'password' => 'Contraseña',
            'password_confirmation' => 'Confirmación de contraseña',
        ];
    }
}
