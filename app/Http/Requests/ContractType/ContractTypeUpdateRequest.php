<?php

namespace App\Http\Requests\ContractType;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ContractTypeUpdateRequest extends FormRequest
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
            'item_id' => ['required', 'exists:items,id', 'in:1'],
            'name' => ['required', 'string', 'max:80', 'unique:subitems,name,' . $this->route('contractTypeId') . ',id,item_id,1'],
            'description' => ['required', 'string', 'max:500'],
            'settings' => ['required', 'array'],
            'settings.health_entity_affiliation' => ['required', 'boolean'],
            'settings.pension_fund_affiliation' => ['required', 'boolean'],
            'settings.compensation_fund_affiliation' => ['required', 'boolean'],
            'settings.risk_manager_affiliation' => ['required', 'boolean'],
            'settings.bank_certificate' => ['required', 'boolean'],
            'settings.fiscal_document_certificate' => ['required', 'boolean'],
            'file_subtypes_id' => ['required', 'array', 'min:1'],
            'file_subtypes_id.*' => ['required', 'exists:subitems,id,item_id,17']
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'exists' => 'No existe en la base de datos.',
            'string' => 'Debe ser una cadena de caracteres.',
            'unique' => 'Ya ha sido tomado.',
            'boolean' => 'Deber ser requerido o no requerido.',
            'max' => 'No debe exceder los :max caracteres.',
            'min' => 'Debe tener al menos :min elementos.',
        ];
    }

    public function attributes()
    {
        return [
            'item_id' => 'Categorización',
            'name' => 'Nombre',
            'description' => 'Descripción',
            'settings' => 'Configuración',
            'settings.health_entity_affiliation' => 'Afiliación Sistema de salud',
            'settings.pension_fund_affiliation' => 'Afiliación Fondo de pensión',
            'settings.compensation_fund_affiliation' => 'Afiliación Caja de compensación',
            'settings.risk_manager_affiliation' => 'Afiliación Administradora de riesgos laborales',
            'settings.bank_certificate' => 'Certificado bancario',
            'settings.fiscal_document_certificate' => 'Certificado Documento fiscal',
            'file_subtypes_id' => 'Plantillas contractuales',
            'file_subtypes_id.*' => 'Plantilla contractual'
        ];
    }
}
