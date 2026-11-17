<?php

namespace App\Http\Requests\Termination;

use App\Models\Termination;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TerminationRejectedRequest extends FormRequest
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
        return [];
    }

    public function messages()
    {
        return [];
    }

    public function attributes()
    {
        return [
            'status' => 'Estado'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $termination = Termination::find($this->route('terminationId'));
            if (!$termination) {
                $validator->errors()->add('status', 'Terminación de contrato no encontrada.');
                return;
            }

            if (!in_array($termination->status, [Termination::REVIEW, Termination::APPROVED], true)) {
                $validator->errors()->add('status', 'Los estados permitidos para realizar esta acción son: '. implode(', ', [Termination::REVIEW, Termination::APPROVED]) . '.');
            }
        });
    }
}
