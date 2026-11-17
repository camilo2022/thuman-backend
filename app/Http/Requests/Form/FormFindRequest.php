<?php

namespace App\Http\Requests\Form;

use App\Models\Enterprise;
use App\Models\Entity;
use App\Models\Headquarter;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class FormFindRequest extends FormRequest
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
        $type = $this->input('assignable_type');

        return [
            'form_type_id' => [$this->route('formId') ? 'nullable' : 'required', 'exists:subitems,id,item_id,18'],
            'assignable_type' => ['nullable', 'in:' . implode(',', [Enterprise::class, Entity::class, Headquarter::class])],
            'assignable_id' => [
                'nullable',
                function ($attribute, $value, $fail) use ($type) {
                    if (!$type) {
                        return;
                    }

                    $exists = false;

                    if ($type === Enterprise::class) {
                        $exists = Enterprise::where('id', $value)->exists();
                    } elseif ($type === Entity::class) {
                        $exists = Entity::where('id', $value)->exists();
                    } elseif ($type === Headquarter::class) {
                        $exists = Headquarter::where('id', $value)->exists();
                    }

                    if (!$exists) {
                        $fail('El assignable_id no existe para el assignable_type enviado.');
                    }
                }
            ],
            // 'entity_id' => [$this->route('formId') ? 'nullable' : 'required', 'exists:entities,id'],
            'position_id' => ['nullable', 'exists:positions,id'],
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'string' => 'Debe ser una cadena de caracteres.',
            'numeric' => 'Debe ser un valor numérico.',
            'exists' => 'No existe en la base de datos.',
        ];
    }

    public function attributes()
    {
        return [
            'entity_id' => 'Entidad internacional',
            'position_id' => 'Cargo',
        ];
    }
}
