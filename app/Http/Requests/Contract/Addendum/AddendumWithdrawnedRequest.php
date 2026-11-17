<?php

namespace App\Http\Requests\Contract\Addendum;

use App\Models\Addendum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AddendumWithdrawnedRequest extends FormRequest
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
            'person_observation' => ['required', 'string', 'max:500']
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'string' => 'Debe ser una cadena de caracteres.',
            'max' => 'No debe exceder los :max caracteres.',
        ];
    }

    public function attributes()
    {
        return [
            'person_observation' => 'Observación persona',
            'person_status' => 'Estado persona'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $addendum = Addendum::find($this->route('addendumId'));
            if (!$addendum) {
                $validator->errors()->add('person_status', 'Contrato no encontrada.');
                return;
            }

            if (!in_array($addendum->person_status, [Addendum::PENDING], true)) {
                $validator->errors()->add('person_status', 'Los estados permitidos para realizar esta acción son: '. implode(', ', [Addendum::PENDING]) . '.');
            }
        });
    }
}
