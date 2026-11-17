<?php

namespace App\Http\Requests\BankCertificate;

use App\Models\BankCertificate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class BankCertificateUpdateRequest extends FormRequest
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
            'person_id' => ['required', 'exists:people,id', 'unique:bank_certificates,person_id,' . $this->route('bankCertificateId') . ',id'],
            'country_id' => ['required', 'exists:countries,id'],
            'bank_id' => ['required', 'exists:subitems,id,item_id,14'],
            'bank_account_type_id' => ['required', 'exists:subitems,id,item_id,15'],
            'account_number' => ['required', 'digits_between:10,20'],
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
            'digits_between' => 'Debe tener entre :min y :max dígitos.',
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
            'bank_id' => 'Banco',
            'bank_account_type_id' => 'Tipo de cuenta bancaria',
            'account_number' => 'Número de cuenta bancaria',
            'file' => 'Configuración certificado bancario',
            'file.file' => 'Certificado bancario',
            'file.file_type_id' => 'Tipo de archivo',
            'file.file_subtype_id' => 'Subtipo de archivo',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $bankCertificate = BankCertificate::find($this->route('bankCertificateId'));
            if (!$bankCertificate) {
                $validator->errors()->add('status', 'Certificación bancaria no encontrada.');
                return;
            }

            if (!in_array($bankCertificate->status, [BankCertificate::PENDING, BankCertificate::REFRESHED, BankCertificate::APPROVED], true)) {
                $validator->errors()->add('status', 'Los estados permitidos para realizar esta acción son: '. implode(', ', [BankCertificate::PENDING, BankCertificate::REFRESHED, BankCertificate::APPROVED]) . '.');
            }
        });
    }
}
