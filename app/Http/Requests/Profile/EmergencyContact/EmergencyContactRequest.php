<?php

namespace App\Http\Requests\Profile\EmergencyContact;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class EmergencyContactFindRequest extends FormRequest
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
            'profile_id' => [$this->route('emergencyContactId') ? 'nullable' : 'required', 'numeric', 'exists:profiles,id']
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'exists' => 'No existe en la base de datos.',
            'numeric' => 'Debe ser un valor numérico.'
        ];
    }

    public function attributes()
    {
        return [
            'profile_id' => 'Perfil'
        ];
    }
}
