<?php

namespace App\Http\Requests\User;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Hash;

class UserUpdateRequest extends FormRequest
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
            'person_id' => ['required', 'exists:people,id', 'unique:users,person_id,' . $this->route('userId') .',id'],
            'current_password' => ['required', 'string'],
            'password' => [
                'required', 'string', 'min:8', 'max:75', 'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&.,;:])[A-Za-z\d@$!%*#?&.,;:]{8,}$/'
            ],
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'string' => 'Debe ser una cadena de caracteres.',
            'email' => 'Debe ser una dirección de correo electrónico válida.',
            'unique' => 'Ya ha sido tomado.',
            'max' => 'No debe exceder los :max caracteres.',
            'min' => 'Debe tener al menos :min caracteres.',
            'date' => 'Debe ser una fecha válida.',
            'exists' => 'No existe en la base de datos.',
            'confirmed' => 'No coincide con la confirmación.',
            'regex' => 'Debe contener al menos una letra mayúscula, una letra minúscula, un número y un carácter especial.',
        ];
    }

    public function attributes()
    {
        return [
            'person_id' => 'Persona',
            'current_password' => 'Contraseña actual',
            'password' => 'Nueva contraseña',
            'password_confirmation' => 'Confirmación de la nueva contraseña',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $user = User::find($this->route('userId'));

            if (!$user || !Hash::check($this->input('current_password'), $user->password)) {
                $validator->errors()->add('current_password', 'La contraseña actual no es correcta.');
            }
        });
    }
}
