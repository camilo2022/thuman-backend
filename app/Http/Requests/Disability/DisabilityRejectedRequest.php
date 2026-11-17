<?php

namespace App\Http\Requests\Disability;

use App\Models\Disability;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class DisabilityRejectedRequest extends FormRequest
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
            $disability = Disability::find($this->route('disabilityId'));
            if (!$disability) {
                $validator->errors()->add('status', 'Incapacidad no encontrada.');
                return;
            }

            if (!in_array($disability->status, [Disability::REVIEW, Disability::APPROVED], true)) {
                $validator->errors()->add('status', 'Los estados permitidos para realizar esta acción son: '. implode(', ', [Disability::REVIEW, Disability::APPROVED]) . '.');
            }
        });
    }
}
