<?php

namespace App\Http\Requests\RiskManagerAffiliation;

use App\Models\RiskManagerAffiliation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RiskManagerAffiliationUpdateRequest extends FormRequest
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
            'person_id' => ['required', 'exists:people,id', 'unique:risk_manager_affiliations,person_id,' . $this->route('riskManagerAffiliationId') . ',id'],
            'country_id' => ['required', 'exists:countries,id'],
            'risk_manager_id' => ['required', 'exists:subitems,id,item_id,24'],
            'file' => ['required', 'array'],
            'file.file' => ['required', 'file', 'mimetypes:application/pdf', 'max:2048'],
            'file.file_type_id' => ['required_with:file.file', 'exists:subitems,id,item_id,16', 'in:100'],
            'file.file_subtype_id' => ['required_with:file.file', 'exists:subitems,id,item_id,17', 'in:129']
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'unique' => 'Ya ha sido tomado.',
            'exists' => 'No existe en la base de datos.',
            'max' => 'No debe exceder los :max KB.',
            'array' => 'Debe ser una lista de datos.',
            'mimetypes' => 'Extensión inválida. Permitidas :values.',
            'file' => 'Debe ser un archivo.',
        ];
    }

    public function attributes()
    {
        return [
            'person_id' => 'Persona',
            'country_id' => 'Pais',
            'risk_manager_id' => 'Administradora de riesgo laboral',
            'file' => 'Configuración certificado afiliación administradora de riesgo laboral',
            'file.file' => 'Certificado afiliación administradora de riesgo laboral',
            'file.file_type_id' => 'Tipo de archivo',
            'file.file_subtype_id' => 'Subtipo de archivo',
            'status' => 'Estado'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $riskManagerAffiliation = RiskManagerAffiliation::find($this->route('riskManagerAffiliationId'));
            if (!$riskManagerAffiliation) {
                $validator->errors()->add('status', 'Afiliación a la administradora de riesgos laborales no encontrada.');
                return;
            }

            if (!in_array($riskManagerAffiliation->status, [RiskManagerAffiliation::PENDING, RiskManagerAffiliation::REFRESHED, RiskManagerAffiliation::APPROVED], true)) {
                $validator->errors()->add('status', 'Los estados permitidos para realizar esta acción son: '. implode(', ', [RiskManagerAffiliation::PENDING, RiskManagerAffiliation::REFRESHED, RiskManagerAffiliation::APPROVED]) . '.');
            }
        });
    }
}
