<?php

namespace App\Http\Requests\CompensationFundAffiliation;

use App\Models\CompensationFundAffiliation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CompensationFundAffiliationUpdateRequest extends FormRequest
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
            'person_id' => ['required', 'exists:people,id', 'unique:compensation_fund_affiliations,person_id,' . $this->route('compensationFundAffiliationId') . ',id'],
            'country_id' => ['required', 'exists:countries,id'],
            'compensation_fund_id' => ['required', 'exists:subitems,id,item_id,27'],
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
            'required_with' => 'Es requerido cuando :values está presente.',
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
            'compensation_fund_id' => 'Caja de compensación',
            'file' => 'Configuración certificado afiliación caja de compensación',
            'file.file' => 'Certificado afiliación caja de compensación',
            'file.file_type_id' => 'Tipo de archivo',
            'file.file_subtype_id' => 'Subtipo de archivo',
            'status' => 'Estado'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $compensationFundAffiliation = CompensationFundAffiliation::find($this->route('compensationFundAffiliationId'));
            if (!$compensationFundAffiliation) {
                $validator->errors()->add('status', 'Afiliación a la caja de compensación no encontrada.');
                return;
            }

            if (!in_array($compensationFundAffiliation->status, [CompensationFundAffiliation::PENDING, CompensationFundAffiliation::REFRESHED], true)) {
                $validator->errors()->add('status', 'Los estados permitidos para realizar esta acción son: '. implode(', ', [CompensationFundAffiliation::PENDING, CompensationFundAffiliation::REFRESHED]) . '.');
            }
        });
    }
}
