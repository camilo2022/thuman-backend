<?php

namespace App\Http\Requests\Contract\Addendum;

use App\Models\Addendum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AddendumAllRequest extends FormRequest
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
            'search' => ['nullable', 'string', 'max:100'],
            'perPage' => ['nullable', 'numeric'],
            'person_status' => ['nullable', 'in:' . implode(',', [Addendum::PENDING, Addendum::WITHDRAWNED, Addendum::REFRESHED, Addendum::SIGNED])],
            'legal_representative_status' => ['nullable', 'in:' . implode(',', [Addendum::REVIEW, Addendum::APPROVED, Addendum::REJECTED, Addendum::SIGNED])]
        ];
    }

    public function messages()
    {
        return [
            'string' => 'Debe ser una cadena de caracteres.',
            'numeric' => 'Debe ser un valor numérico.'
        ];
    }

    public function attributes()
    {
        return [
            'search' => 'Término de búsqueda',
            'perPage' => 'Número de registros por página'
        ];
    }
}
