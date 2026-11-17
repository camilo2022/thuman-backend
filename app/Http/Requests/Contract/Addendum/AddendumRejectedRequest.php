<?php

namespace App\Http\Requests\Contract\Addendum;

use App\Models\Addendum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AddendumRejectedRequest extends FormRequest
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
            'legal_representative_status' => 'Estado representante legal'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $addendum = Addendum::find($this->route('addendumId'));
            if (!$addendum) {
                $validator->errors()->add('legal_representative_status', 'Otro sí no encontrada.');
                return;
            }

            if (!in_array($addendum->legal_representative_status, [Addendum::REVIEW, Addendum::APPROVED], true)) {
                $validator->errors()->add('legal_representative_status', 'Los estados permitidos para realizar esta acción son: '. implode(', ', [Addendum::REVIEW, Addendum::APPROVED]) . '.');
            }
        });
    }
}
