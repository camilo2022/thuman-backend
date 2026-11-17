<?php

namespace App\Http\Requests\Resignation;

use App\Models\Resignation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ResignationRejectedRequest extends FormRequest
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
            $resignation = Resignation::find($this->route('resignationId'));
            if (!$resignation) {
                $validator->errors()->add('status', 'Renuncia no encontrada.');
                return;
            }

            if (!in_array($resignation->status, [Resignation::REVIEW, Resignation::APPROVED], true)) {
                $validator->errors()->add('status', 'Los estados permitidos para realizar esta acción son: '. implode(', ', [Resignation::REVIEW, Resignation::APPROVED]) . '.');
            }
        });
    }
}
