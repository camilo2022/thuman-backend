<?php

namespace App\Http\Requests\Contract;

use App\Models\Contract;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ContractApprovedRequest extends FormRequest
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
        return [];
    }

    public function messages()
    {
        return [];
    }

    public function attributes()
    {
        return [
            'legal_representative_status' => 'Estado representante legal'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $contract = Contract::find($this->route('contractId'));
            if (!$contract) {
                $validator->errors()->add('legal_representative_status', 'Contrato no encontrada.');
                return;
            }

            if (!in_array($contract->legal_representative_status, [Contract::REVIEW], true)) {
                $validator->errors()->add('legal_representative_status', 'Los estados permitidos para realizar esta acción son: '. implode(', ', [Contract::REVIEW]) . '.');
            }
        });
    }
}
