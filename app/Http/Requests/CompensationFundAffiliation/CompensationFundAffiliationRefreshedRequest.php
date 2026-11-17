<?php

namespace App\Http\Requests\CompensationFundAffiliation;

use App\Models\CompensationFundAffiliation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CompensationFundAffiliationRefreshedRequest extends FormRequest
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
            $compensationFundAffiliation = CompensationFundAffiliation::find($this->route('compensationFundAffiliationId'));
            if (!$compensationFundAffiliation) {
                $validator->errors()->add('status', 'Afiliación a la caja de compensación no encontrada.');
                return;
            }

            if (!in_array($compensationFundAffiliation->status, [CompensationFundAffiliation::PENDING], true)) {
                $validator->errors()->add('status', 'Los estados permitidos para realizar esta acción son: '. implode(', ', [CompensationFundAffiliation::PENDING]) . '.');
            }
        });
    }
}
