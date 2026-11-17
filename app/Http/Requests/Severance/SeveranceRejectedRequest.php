<?php

namespace App\Http\Requests\Severance;

use App\Models\Severance;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SeveranceRejectedRequest extends FormRequest
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
            $severance = Severance::find($this->route('severanceId'));
            if (!$severance) {
                $validator->errors()->add('status', 'Cesantías no encontrada.');
                return;
            }

            if (!in_array($severance->status, [Severance::REVIEW, Severance::APPROVED], true)) {
                $validator->errors()->add('status', 'Los estados permitidos para realizar esta acción son: '. implode(', ', [Severance::REVIEW, Severance::APPROVED]) . '.');
            }
        });
    }
}
