<?php

namespace App\Http\Requests\FiscalDocumentCertificate;

use App\Models\FiscalDocumentCertificate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class FiscalDocumentCertificateUpdateRequest extends FormRequest
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
            'person_id' => ['required', 'exists:people,id', 'unique:fiscal_document_certificates,person_id,' . $this->route('fiscalDocumentCertificateId') . ',id'],
            'country_id' => ['required', 'exists:countries,id'],
            'fiscal_document_id' => ['required', 'exists:subitems,id,item_id,31'],
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
            'fiscal_document_id' => 'Documento fiscal',
            'file' => 'Configuración certificado documento fiscal',
            'file.file' => 'Certificado documento fiscal',
            'file.file_type_id' => 'Tipo de archivo',
            'file.file_subtype_id' => 'Subtipo de archivo',
            'status' => 'Estado'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $fiscalDocumentCertificate = FiscalDocumentCertificate::find($this->route('fiscalDocumentCertificateId'));
            if (!$fiscalDocumentCertificate) {
                $validator->errors()->add('status', 'Certificación documento fiscal no encontrada.');
                return;
            }

            if (!in_array($fiscalDocumentCertificate->status, [FiscalDocumentCertificate::PENDING, FiscalDocumentCertificate::REFRESHED, FiscalDocumentCertificate::APPROVED], true)) {
                $validator->errors()->add('status', 'Los estados permitidos para realizar esta acción son: '. implode(', ', [FiscalDocumentCertificate::PENDING, FiscalDocumentCertificate::REFRESHED, FiscalDocumentCertificate::APPROVED]) . '.');
            }
        });
    }
}
