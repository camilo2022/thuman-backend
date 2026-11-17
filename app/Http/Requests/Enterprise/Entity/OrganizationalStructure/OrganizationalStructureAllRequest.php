<?php

namespace App\Http\Requests\Enterprise\Entity\OrganizationalStructure;

use App\Models\OrganizationalStructure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class OrganizationalStructureAllRequest extends FormRequest
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
            'perPage' => ['nullable', 'integer', 'min:1'],
            'entity_id' => ['nullable', 'integer', 'exists:entities,id'],
            'position_id' => ['nullable', 'integer', 'exists:positions,id'],
            'parent_position_id' => ['nullable', 'integer', 'exists:positions,id'],
            'status' => [
                'nullable',
                'in:' . implode(',', OrganizationalStructure::statuses())
            ],
        ];
    }

    public function messages()
    {
        return [
            'string' => 'Debe ser una cadena de caracteres.',
            'integer' => 'Debe ser un número entero.',
            'min' => 'El valor debe ser mínimo :min.',
            'exists' => 'No existe en la base de datos.',
            'in' => 'El valor seleccionado no es válido.',
        ];
    }

    public function attributes()
    {
        return [
            'search' => 'Término de búsqueda',
            'perPage' => 'Número de registros por página',
            'entity_id' => 'Entidad',
            'position_id' => 'Cargo',
            'parent_position_id' => 'Cargo superior',
            'status' => 'Estado',
        ];
    }
}
