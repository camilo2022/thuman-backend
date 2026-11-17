<?php

namespace App\Http\Requests\User;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserToggleRequest extends FormRequest
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
            'permission_id' => ['required:country_id', 'exists:permissions,id']
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'exists' => 'No existe en la base de datos.'
        ];
    }

    public function attributes()
    {
        return [
            'permission_id' => 'Permiso',
            'user_id' => 'Usuario'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $user = User::with('person.employee')->find($this->route('userId'));
            if (!$user) {
                $validator->errors()->add('user_id', 'Usuario no encontrado.');
                return;
            }

            if (!$user?->person?->employee) {
                $validator->errors()->add('user_id', 'No es un empleado');
            }
        });
    }
}
