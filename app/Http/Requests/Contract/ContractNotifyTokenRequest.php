<?php

namespace App\Http\Requests\Contract;

use App\Models\Contract;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ContractNotifyTokenRequest extends FormRequest
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
            'application_id' => [$this->route('contractId') ? 'nullable' : 'required', 'exists:contracts,application_id']
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
            'application_id' => 'Aplicación',
            'person_status' => 'Estado persona',
            'legal_representative_status' => 'Estado representante legal',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $contract = Contract::when($this->route('contractId'), function ($query) {
                    $query->where('id', $this->route('contractId'));
                })
                ->when(!$this->route('contractId'), function ($query) {
                    $query->when($this->filled('application_id'), function ($query) {
                        $query->where('application_id', $this->integer('application_id'));
                    });
                })
                ->firstOrFail();
            if (!$contract) {
                $validator->errors()->add('person_status', 'Contrato no encontrado.');
                return;
            }

            if (!in_array($contract->person_status, [Contract::PENDING, Contract::REFRESHED], true)) {
                $validator->errors()->add('person_status', 'Los estados permitidos para realizar esta acción son: '. implode(', ', [Contract::PENDING, Contract::REFRESHED]) . '.');
            }

            if (!in_array($contract->legal_representative_status, [Contract::APPROVED, Contract::SIGNED], true)) {
                $validator->errors()->add('legal_representative_status', 'Los estados permitidos para realizar esta acción son: '. implode(', ', [Contract::APPROVED, Contract::SIGNED]) . '.');
            }
        });
    }
}
