<?php

namespace App\Http\Requests\Enterprise\Entity\OrganizationalStructure;

use App\Models\OrganizationalStructure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class OrganizationalStructureRejectedRequest extends FormRequest
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
            $organizationalStructure = OrganizationalStructure::find($this->route('organizationalStructureId'));
            if (!$organizationalStructure) {
                $validator->errors()->add('status', 'Estructura organizativa no encontrada.');
                return;
            }

            if ($organizationalStructure->status !== OrganizationalStructure::PENDING) {
                $validator->errors()->add('status', 'Los estados permitidos para realizar esta acción son: ' . implode(', ', [OrganizationalStructure::PENDING]) . '.');
            }
        });
    }
}
