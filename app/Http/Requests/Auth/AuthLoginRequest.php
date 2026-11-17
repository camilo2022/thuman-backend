<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AuthLoginRequest extends FormRequest
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
            'email' => ['required_without:document_number', 'nullable', 'email'/*, 'exists:people,email'*/],
            'document_number' => ['required_without:email', 'nullable', 'string'/*, 'exists:people,document_number'*/],
            'password' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Es requerido.',
            'required_without' => 'Es requerido.',
            'email' => 'Debe ser una dirección de correo electrónico válida.',
            'exists' => 'No existe en la base de datos.',
            'string' => 'Debe ser una cadena de caracteres.',
        ];
    }

    public function attributes(): array
    {
        return [
            'email' => 'Correo electrónico',
            'document_number' => 'Número de documento',
            'password' => 'Contraseña',
        ];
    }
}
