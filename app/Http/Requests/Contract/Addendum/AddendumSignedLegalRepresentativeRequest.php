<?php

namespace App\Http\Requests\Contract\Addendum;

use App\Models\Addendum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AddendumSignedLegalRepresentativeRequest extends FormRequest
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
            'person_status' => 'Estado persona',
            'legal_representative_status' => 'Estado representante legal',
            'legal_representative' => 'Representante legal'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $addendum = Addendum::with(['contract.headquarter.entity.person.signature', 'headquarter.entity.person.signature'])->find($this->route('addendumId'));
            if (!$addendum) {
                $validator->errors()->add('legal_representative_status', 'Otro sí no encontrado.');
                return;
            }

            if (!in_array($addendum->legal_representative_status, [Addendum::APPROVED], true)) {
                $validator->errors()->add('legal_representative_status', 'Los estados permitidos para realizar esta acción son: '. implode(', ', [Addendum::APPROVED]) . '.');
            }

            if (!in_array($addendum->person_status, [Addendum::PENDING, Addendum::SIGNED], true)) {
                $validator->errors()->add('person_status', 'Los estados permitidos para realizar esta acción son: '. implode(', ', [Addendum::PENDING, Addendum::SIGNED]) . '.');
            }

            if(!(($addendum->headquarter ?? $addendum->contract?->headquarter)?->entity?->person?->signature)) {
                $validator->errors()->add('legal_representative', 'Para realizar esta acción debe tener una firma electrónica configurada.');
            }
        });
    }
}
