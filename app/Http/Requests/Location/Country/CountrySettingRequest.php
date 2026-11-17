<?php

namespace App\Http\Requests\Location\Country;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CountrySettingRequest extends FormRequest
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
            'settings.validations.*.regex' => ['required', 'string', 'distinct', 'max:255'],
            'settings.validations.*.message' => ['required', 'string', 'max:255'],
            'settings.validations.*.created_at' => ['required', 'date'],
            'settings.validations.*.updated_at' => ['nullable', 'date'],
            'settings.validations.*.deleted_at' => ['nullable', 'date']
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'string' => 'Debe ser una cadena de caracteres.',
            'distinct'  => 'Ya ha sido tomado en otra validación.',
            'unique' => 'Ya ha sido tomado.',
            'numeric' => 'Debe ser un valor numérico.',
            'date' => 'Debe ser una fecha válida.',
            'array' => 'Debe ser una lista de datos.',
            'max' => 'No debe exceder los :max caracteres.',
            'min' => 'Debe tener minimo :min elementos.',
        ];
    }

    public function attributes()
    {
        $attributes = [];
        foreach ($this->input('settings.validations', []) as $i => $v) {
            $n = $i + 1;
            $base = "settings.validations.$i";
            $attributes["$base.id"] = "Id (validación n° $n)";
            $attributes["$base.regex"] = "Expresión regular (validación n° $n)";
            $attributes["$base.message"] = "Mensaje (validación n° $n)";
            $attributes["$base.created_at"] = "Fecha de creación (validación n° $n)";
            $attributes["$base.updated_at"] = "Fecha de actualización (validación n° $n)";
            $attributes["$base.deleted_at"] = "Fecha de eliminación (validación n° $n)";
        }

        return [
            'settings' => 'Configuración',
            'settings.validations' => 'Validaciones',
            ...$attributes
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            foreach ($this->input('settings.validations', []) as $i => $v) {
                $regex = $v['regex'] ?? null;
                if (!is_string($regex) || @preg_match($regex, '') === false) {
                    $validator->errors()->add(
                        "settings.validations.$i.regex",
                        'Debe ser una expresión regular válida.'
                    );
                }
            }
            $seen = [];
            foreach ($this->input('settings.validations', []) as $i => $v) {
                if (!array_key_exists('regex', $v)) {
                    continue;
                }
                $regex = (string) $v['regex']; // normalizamos a string
                if (isset($seen[$regex])) {
                    $primera = $seen[$regex] + 1;
                    $validator->errors()->add(
                        "settings.validations.$i.regex",
                        "Ya ha sido tomado en la validación n° {$primera}."
                    );
                } else {
                    $seen[$regex] = $i;
                }
            }
        });
    }
}
