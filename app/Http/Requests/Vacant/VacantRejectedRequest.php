<?php

namespace App\Http\Requests\Vacant;

use App\Models\Vacant;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class VacantRejectedRequest extends FormRequest
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
            $vacant = Vacant::find($this->route('vacantId'));
            if (!$vacant) {
                $validator->errors()->add('status', 'Vacante no encontrada.');
                return;
            }

            if (!in_array($vacant->status, [Vacant::REVIEW, Vacant::APPROVED], true)) {
                $validator->errors()->add('status', 'Los estados permitidos para realizar esta acción son: '. implode(', ', [Vacant::REVIEW, Vacant::APPROVED]) . '.');
            }
        });
    }
}
