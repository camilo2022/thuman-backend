<?php

namespace App\Http\Requests\Contract\Policy;

use App\Models\Policy;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class PolicyCanceledRequest extends FormRequest
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
            $policy = Policy::find($this->route('policyId'));
            if (!$policy) {
                $validator->errors()->add('status', 'Póliza no encontrada.');
                return;
            }

            if (!in_array($policy->status, [Policy::REVIEW, Policy::ACTIVED], true)) {
                $validator->errors()->add('status', 'Los estados permitidos para realizar esta acción son: '. implode(', ', [Policy::REVIEW, Policy::ACTIVED]) . '.');
            }
        });
    }
}
