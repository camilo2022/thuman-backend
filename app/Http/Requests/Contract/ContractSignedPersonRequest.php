<?php

namespace App\Http\Requests\Contract;

use App\Models\Contract;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ContractSignedPersonRequest extends FormRequest
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

    protected function prepareForValidation()
    {
        if (is_string($this->settings)) {
            $this->merge(['settings' => json_decode($this->settings, true) ?? []]);
        }

        $this->merge([
            'settings.accept_terms' => filter_var($this->input('settings.accept_terms'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
            'settings.accept_privacy_policy' => filter_var($this->input('settings.accept_privacy_policy'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE)
        ]);
    }

    public function rules()
    {
        return [
            'signature.file' => ['image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'signature.file_type_id' => ['required', 'exists:subitems,id,item_id,16', 'in:100'],
            'signature.file_subtype_id' => ['required', 'exists:subitems,id,item_id,17', 'in:130'],
            'settings' => ['required', 'array'],
            'settings.accept_terms' => ['required', 'accepted'],
            'settings.accept_privacy_policy' => ['required', 'accepted'],
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'max' => 'No debe exceder los :max KB.',
            'image' => 'Debe ser una imagen.',
            'mimes' => 'Extensión inválida. Permitidas :values.',
            'exists' => 'No existe en la base de datos.',
            'array' => 'Debe ser una lista de datos.',
            'accepted' => 'Debe aceptar este campo.'
        ];
    }

    public function attributes()
    {
        return [
            'person_status' => 'Estado persona',
            'legal_representative_status' => 'Estado representante legal',
            'signature' => 'Configuración firma',
            'signature.file' => 'Firma',
            'signature.file_type_id' => 'Tipo de archivo',
            'signature.file_subtype_id' => 'Subtipo de archivo',
            'settings' => 'Configuración',
            'settings.accept_terms' => 'Terminos y condiciones',
            'settings.accept_privacy_policy' => 'Políticas de privacidad'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $contract = Contract::with('person.signature')->find($this->route('contractId'));
            if (!$contract) {
                $validator->errors()->add('person_status', 'Contrato no encontrada.');
                return;
            }

            if (!in_array($contract->legal_representative_status, [Contract::APPROVED, Contract::SIGNED], true)) {
                $validator->errors()->add('legal_representative_status', 'Los estados permitidos para realizar esta acción son: '. implode(', ', [Contract::APPROVED, Contract::SIGNED]) . '.');
            }

            if (!in_array($contract->person_status, [Contract::PENDING], true)) {
                $validator->errors()->add('person_status', 'Los estados permitidos para realizar esta acción son: '. implode(', ', [Contract::PENDING]) . '.');
            }

            if (!$contract->person?->signature && !$this->hasFile('signature.file')) {
                $validator->errors()->add('signature.file', 'Debe cargar la firma, ya que no tiene una firma registrada.');
            }
        });
    }
}
