<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AuthLogoutRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Error de validación.',
            'attributes' => $this->attributes(),
            'errors' => $validator->errors()
        ], 422));
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id']
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Es requerido.',
            'exists' => 'No existe en la base de datos.',
        ];
    }

    public function attributes(): array
    {
        return [
            'user_id' => 'Usuario'
        ];
    }
}
