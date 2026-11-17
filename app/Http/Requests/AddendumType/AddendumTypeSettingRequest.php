<?php

namespace App\Http\Requests\AddendumType;

use App\Models\Item;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AddendumTypeSettingRequest extends FormRequest
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
            'item_id' => ['required', 'exists:items,id', 'in:38'],
            'name' => ['required', 'string', 'max:80', 'unique:subitems,name,NULL,id,item_id,38'],
            'description' => ['required', 'string', 'max:500'],
            'settings' => ['required', 'array'],
            'settings.validations' => ['required', 'array', 'min:1'],
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
            'array' => 'Debe ser una lista de datos.',
            'min' => 'Debe tener minimo :min elementos.'
        ];
    }

    public function attributes()
    {
        return [
            'item_id' => 'Categorización',
            'name' => 'Nombre',
            'description' => 'Descripción',
            'settings' => 'Configuración',
            'settings.validations' => 'Validaciones',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $item = Item::find($this->integer('item_id'));
            if (!$item) {
                $validator->errors()->add('item_id', 'Categorización no encontrada.');
                return;
            }

            $attributes = [];

            foreach ($item->settings->fields ?? [] as $key => $field) {
                $attributes["settings.validations.$key"] = $field->label;
            }

            $validator->setAttributeNames(array_merge($this->attributes(), $attributes));

            foreach ($this->input('settings.validations', []) as $key => $rule) {

                if (!array_key_exists($key, $item->settings->fields ?? [])) {
                    $validator->errors()->add("settings.validations.$key", "El campo '$key' no está permitido para este tipo.");
                    continue;
                }

                if (!in_array($rule, ['required', 'nullable'], true)) {
                    $validator->errors()->add("settings.validations.$key", "La regla debe ser 'required' o 'nullable'.");
                }
            }
        });
    }
}
