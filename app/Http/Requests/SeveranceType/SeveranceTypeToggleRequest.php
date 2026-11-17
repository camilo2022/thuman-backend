<?php

namespace App\Http\Requests\SeveranceType;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SeveranceTypeToggleRequest extends FormRequest
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
            'settings' => ['required', 'array'],
            'settings.validations' => ['required', 'array', 'min:1'],
            'settings.validations.*' => ['required', 'array'],
            'settings.validations.*.id' => ['required', 'numeric', 'distinct'],
            'settings.validations.*.country_id' => ['required', 'exists:countries,id', 'distinct'],
            'settings.validations.*.created_at' => ['required', 'date'],
            'settings.validations.*.updated_at' => ['nullable', 'date'],
            'settings.validations.*.deleted_at' => ['nullable', 'date']
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'exists' => 'No existe en la base de datos.',
            'string' => 'Debe ser una cadena de caracteres.',
            'unique' => 'Ya ha sido tomado.',
            'distinct'  => 'Ya ha sido tomado en otra validación.',
            'array' => 'Debe ser una lista de datos.',
            'max' => 'No debe exceder los :max caracteres.',
            'min' => 'Debe tener minimo :min elementos.',
            'regex' => 'Formato de abreviatura inválido.'
        ];
    }

    public function attributes()
    {
        $attributes = [];
        foreach ($this->input('settings.validations', []) as $i => $validation) {
            $n = $i + 1;
            $base = "settings.validations.$i";
            $attributes["$base.id"] = "Id (validación n° $n)";
            $attributes["$base.country_id"] = "País (validación n° $n)";
            $attributes["$base.created_at"] = "Fecha de creación (validación n° $n)";
            $attributes["$base.updated_at"] = "Fecha de actualización (validación n° $n)";
            $attributes["$base.deleted_at"] = "Fecha de eliminación (validación n° $n)";
        }

        return [
            'item_id' => 'Categorización',
            'name' => 'Nombre',
            'description' => 'Descripción',
            'settings' => 'Configuración',
            'settings.validations' => 'Validaciones',
            ...$attributes
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $seen = [];
            foreach ($this->input('settings.validations', []) as $i => $validation) {
                if (!array_key_exists('country_id', $validation)) {
                    continue;
                }

                $cid = (string) $validation['country_id'];
                if (isset($seen[$cid])) {
                    $primera = $seen[$cid] + 1;
                    $validator->errors()->add("settings.validations.$i.country_id", "Ya ha sido tomado en la validación n° {$primera}.");
                } else {
                    $seen[$cid] = $i;
                }
            }
        });

    }
}
