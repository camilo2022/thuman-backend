<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class EmployeeSettingRequest extends FormRequest
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
            'settings.endowment' => ['required', 'array', 'size:3'],
            'settings.endowment.top' => ['required', 'array'],
            'settings.endowment.top.size_id' => ['required', 'numeric', 'exists:subitems,id,item_id,54'],
            'settings.endowment.bottom' => ['required', 'array'],
            'settings.endowment.bottom.size_id' => ['required', 'numeric', 'exists:subitems,id,item_id,54'],
            'settings.endowment.footwear' => ['required', 'array'],
            'settings.endowment.footwear.size_id' => ['required', 'numeric', 'exists:subitems,id,item_id,54'],
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'array' => 'Debe ser una lista de datos.',
            'numeric' => 'Debe ser un valor numérico.',
            'exists' => 'No existe en la base de datos.'
        ];
    }

    public function attributes()
    {
        $attributes = [];

        foreach ($this->input('settings.channels', []) as $i => $channel) {
            $n = $i + 1;
            $base = "settings.channels.$i";

            $attributes["$base.notification_channel_id"] = "Canal de notificación (configuración n° $n)";
            $attributes["$base.title"] = "Título (configuración n° $n)";
            $attributes["$base.message"] = "Mensaje (configuración n° $n)";
        }

        return [
            'settings' => 'Configuración',
            'settings.channels' => 'Canales de notificación',
            ...$attributes
        ];
    }
}
