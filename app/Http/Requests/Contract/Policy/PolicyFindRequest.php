<?php

namespace App\Http\Requests\Contract\Policy;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class PolicyFindRequest extends FormRequest
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
            'policy_uuid' => [$this->route('policyId') ? 'nullable' : 'required', 'uuid', 'exists:policies,uuid']
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
            'application_id' => 'Aplicación',
            'contract_uuid' => 'Contrato'
        ];
    }
}
