<?php

namespace App\Http\Requests\NotificationType;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class NotificationTypeSettingRequest extends FormRequest
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
            'settings.channels' => ['required', 'array', 'min:1'],
            'settings.channels.*' => ['required', 'array'],
            'settings.channels.*.notification_channel_id' => ['required', 'exists:subitems,id,item_id,23', 'distinct'],
            'settings.channels.*.title' => ['required', 'string', 'max:255'],
            'settings.channels.*.message' => ['required', 'string', 'max:5000'],
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'array' => 'Debe ser una lista de datos.',
            'string' => 'Debe ser una cadena de caracteres.',
            'exists' => 'No existe en la base de datos.',
            'distinct' => 'Ya ha sido tomado en otra configuración.',
            'min' => 'Debe tener al menos :min elementos.',
            'max' => 'No debe exceder los :max caracteres.',
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
