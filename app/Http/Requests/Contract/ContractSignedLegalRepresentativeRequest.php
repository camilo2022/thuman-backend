<?php

namespace App\Http\Requests\Contract;

use App\Models\Contract;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ContractSignedLegalRepresentativeRequest extends FormRequest
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
            'person_status' => 'Estado persona',
            'legal_representative_status' => 'Estado representante legal',
            'legal_representative' => 'Representante legal'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $contract = Contract::with('headquarter.entity.person.signature')->find($this->route('contractId'));
            if (!$contract) {
                $validator->errors()->add('legal_representative_status', 'Contrato no encontrado.');
                return;
            }

            if (!in_array($contract->legal_representative_status, [Contract::APPROVED], true)) {
                $validator->errors()->add('legal_representative_status', 'Los estados permitidos para realizar esta acción son: '. implode(', ', [Contract::APPROVED]) . '.');
            }

            if (!in_array($contract->person_status, [Contract::PENDING, Contract::SIGNED], true)) {
                $validator->errors()->add('person_status', 'Los estados permitidos para realizar esta acción son: '. implode(', ', [Contract::PENDING, Contract::SIGNED]) . '.');
            }

            if(!$contract->headquarter->entity->person->signature) {
                $validator->errors()->add('legal_representative', 'Para realizar esta acción debe tener una firma electrónica configurada.');
            }
        });
    }
}
