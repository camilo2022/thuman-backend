<?php

namespace App\Http\Requests\FiscalDocumentCertificate;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class FiscalDocumentCertificateFindRequest extends FormRequest
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
            'person_id' => [$this->route('fiscalDocumentCertificateId') ? 'nullable' : 'required_without:profile_id', 'numeric', 'exists:people,id'],
            'profile_id' => [$this->route('fiscalDocumentCertificateId') ? 'nullable' : 'required_without:person_id', 'numeric', 'exists:profiles,id']
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'required_without' => 'Es requerido.',
            'exists' => 'No existe en la base de datos.',
            'numeric' => 'Debe ser un valor numérico.'
        ];
    }

    public function attributes()
    {
        return [
            'person_id' => 'Persona',
            'profile_id' => 'Perfil'
        ];
    }
}
