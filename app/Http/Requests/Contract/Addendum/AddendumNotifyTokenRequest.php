<?php

namespace App\Http\Requests\Contract\Addendum;

use App\Models\Addendum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AddendumNotifyTokenRequest extends FormRequest
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
            'addendum_uuid' => [$this->route('addendumId') ? 'nullable' : 'required', 'uuid', 'exists:addendums,uuid']
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'exists' => 'No existe en la base de datos.'
        ];
    }

    public function attributes()
    {
        return [
            'addendum_uuid' => 'Otro sí',
            'person_status' => 'Estado persona',
            'legal_representative_status' => 'Estado representante legal',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $addendum = Addendum::when($this->route('addendumId'), function ($query) {
                    $query->where('id', $this->route('addendumId'));
                })
                ->when(!$this->route('addendumId'), function ($query) {
                    $query->when($this->filled('application_id'), function ($query) {
                        $query->where('application_id', $this->integer('application_id'));
                    });
                })
                ->firstOrFail();
            if (!$addendum) {
                $validator->errors()->add('person_status', 'Otro sí no encontrado.');
                return;
            }

            if (!in_array($addendum->person_status, [Addendum::PENDING, Addendum::REFRESHED], true)) {
                $validator->errors()->add('person_status', 'Los estados permitidos para realizar esta acción son: '. implode(', ', [Addendum::PENDING, Addendum::REFRESHED]) . '.');
            }

            if (!in_array($addendum->legal_representative_status, [Addendum::APPROVED, Addendum::SIGNED], true)) {
                $validator->errors()->add('legal_representative_status', 'Los estados permitidos para realizar esta acción son: '. implode(', ', [Addendum::APPROVED, Addendum::SIGNED]) . '.');
            }
        });
    }
}
