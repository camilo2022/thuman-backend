<?php

namespace App\Http\Requests\FiscalDocumentCertificate;

use App\Models\FiscalDocumentCertificate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class FiscalDocumentCertificateRefreshedRequest extends FormRequest
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
            'observation' => ['required', 'string', 'max:500']
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'string' => 'Debe ser una cadena de caracteres.',
            'max' => 'No debe exceder los :max caracteres.'
        ];
    }

    public function attributes()
    {
        return [
            'observation' => 'Observacion',
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

            if (!in_array($fiscalDocumentCertificate->status, [FiscalDocumentCertificate::PENDING], true)) {
                $validator->errors()->add('status', 'Los estados permitidos para realizar esta acción son: '. implode(', ', [FiscalDocumentCertificate::PENDING]) . '.');
            }
        });
    }
}
