<?php

namespace App\Http\Requests\Contract;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ContractFindRequest extends FormRequest
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
            'application_id' => [
                $this->route('contractId') ? 'nullable' : 'required_without_all:contract_uuid,person_id',
                'nullable',
                'exists:applications,id',
            ],

            'contract_uuid' => [
                $this->route('contractId') ? 'nullable' : 'required_without_all:application_id,person_id',
                'nullable',
                'uuid',
                'exists:contracts,uuid',
            ],

            'person_id' => [
                $this->route('contractId') ? 'nullable' : 'required_without_all:application_id,contract_uuid',
                'nullable',
                'exists:people,id',
            ],
        ];
    }

    public function messages()
    {
        return [
            'required_without_all' => 'Es requerido cuando :values no está presente.',
            'exists' => 'No existe en la base de datos.'
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
