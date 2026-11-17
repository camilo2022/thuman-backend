<?php

namespace App\Http\Requests\Enterprise\Entity\Ally;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class AllyStoreRequest extends FormRequest
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
            'entity_id' => ['required', 'exists:entities,id'],
            'country_id' => ['required', 'exists:countries,id'],
            'name' => ['required', 'string', 'max:80', Rule::unique('allies', 'name')
                ->where(fn($query) => $query->where('entity_id', $this->input('entity_id'))
                    ->where('country_id', $this->input('country_id')))],
            'description' => ['required', 'string', 'max:500'],
            'settings' => ['required', 'array'],
            'settings.works' => ['required', 'array', 'min:1'],
            'settings.works.*' => ['required', 'array'],
            'settings.works.*.days' => ['required', 'array', 'min:1'],
            'settings.works.*.days.*' => ['required', 'integer', 'between:1,7']
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'exists' => 'No existe en la base de datos.',
            'string' => 'Debe ser una cadena de caracteres.',
            'unique' => 'Ya ha sido tomado.',
            'max' => 'No debe exceder los :max caracteres.',
            'min' => 'Debe ser mínimo :min caracteres.',
        ];
    }

    public function attributes()
    {
        return [
            'entity_id' => 'Entidad internacional',
            'country_id' => 'País',
            'name' => 'Nombre',
            'description' => 'Descripción',
            'settings' => 'Configuración',
            'settings.works' => 'Trabajos',
            'settings.works.*' => 'Trabajo',
            'settings.works.*.days' => 'Días',
            'settings.works.*.days.*' => 'Día',
        ];
    }
}
