<?php

namespace App\Http\Requests\NotificationChannel;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class NotificationChannelSettingRequest extends FormRequest
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
            'settings.platforms' => ['required', 'array', 'min:1'],
            'settings.platforms.*' => ['required', 'array'],
            'settings.platforms.*.notification_platform_id' => ['required', 'exists:subitems,id,item_id,22', 'distinct'],
            'settings.platforms.*.variables' => ['required', 'array', 'min:1'],
            'settings.platforms.*.variables.*' => ['required', 'array'],
            'settings.platforms.*.variables.*.label' => ['required', 'string', 'max:100'],
            'settings.platforms.*.variables.*.value' => ['required', 'string', 'max:1000'],
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'array' => 'Debe ser una lista de datos.',
            'string' => 'Debe ser una cadena de caracteres.',
            'distinct' => 'Ya ha sido tomado en otra configuración.',
            'exists' => 'No existe en la base de datos.',
            'min' => 'Debe tener al menos :min elementos.',
            'max' => 'No debe exceder los :max caracteres.',
        ];
    }

    public function attributes()
    {
        $attributes = [];
        foreach ($this->input('settings.platforms', []) as $i => $platform) {
            $n = $i + 1;
            $base = "settings.platforms.$i";

            $attributes["$base.notification_platform_id"] = "Plataforma de notificación (configuración n° $n)";
            $attributes["$base.variables"] =  "Variables (configuración n° $n)";

            foreach (($platform['variables'] ?? []) as $key => $variable) {
                $attributes["$base.variables.$key.label"] = "Etiqueta de la variable '{$key}' (configuración n° $n)";
                $attributes["$base.variables.$key.value"] = "Valor de la variable '{$key}' (configuración n° $n)";
            }
        }

        return [
            'settings' => 'Configuración',
            'settings.platforms' => 'Plataformas de notificación',
            ...$attributes
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            foreach ($this->input('settings.platforms', []) as $i => $platform) {
                foreach (($platform['variables'] ?? []) as $key => $variable) {
                    if (isset($variable['label']) && trim($variable['label']) === '') {
                        $validator->errors()->add("settings.platforms.$i.variables.$key.label", 'Es requerido.');
                    }

                    if (isset($variable['value']) && trim($variable['value']) === '') {
                        $validator->errors()->add("settings.platforms.$i.variables.$key.value", 'Es requerido.');
                    }
                }
            }

            $seen = [];
            foreach ($this->input('settings.platforms', []) as $i => $platform) {
                if (!array_key_exists('notification_platform_id', $platform)) {
                    continue;
                }

                $pid = (string) $platform['notification_platform_id'];
                if (isset($seen[$pid])) {
                    $primera = $seen[$pid] + 1;
                    $validator->errors()->add("settings.platforms.$i.notification_platform_id", "Ya ha sido tomada en la configuración n° {$primera}.");
                } else {
                    $seen[$pid] = $i;
                }
            }
        });
    }
}
