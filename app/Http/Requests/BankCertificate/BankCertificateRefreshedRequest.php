<?php

namespace App\Http\Requests\BankCertificate;

use App\Models\BankCertificate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class BankCertificateRefreshedRequest extends FormRequest
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
            $bankCertificate = BankCertificate::find($this->route('bankCertificateId'));
            if (!$bankCertificate) {
                $validator->errors()->add('status', 'Certificación bancaria no encontrada.');
                return;
            }

            if (!in_array($bankCertificate->status, [BankCertificate::PENDING], true)) {
                $validator->errors()->add('status', 'Los estados permitidos para realizar esta acción son: '. implode(', ', [BankCertificate::PENDING]) . '.');
            }
        });
    }
}
