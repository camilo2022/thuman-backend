<?php

namespace App\Http\Requests\Survey;

use App\Models\Enterprise;
use App\Models\Entity;
use App\Models\Headquarter;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SurveyAllRequest extends FormRequest
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
            'search' => ['nullable', 'string', 'max:100'],
            'perPage' => ['nullable', 'numeric'],
            'form_id' => ['nullable', 'exists:forms,id'],
            'form_type_id' => ['nullable', 'exists:subitems,id,item_id,18'],
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
            'person_id' => ['nullable', 'exists:people,id'],
        ];
    }

    public function messages()
    {
        return [
            'string' => 'Debe ser una cadena de caracteres.',
            'numeric' => 'Debe ser un valor numérico.',
            'exists' => 'No existe en la base de datos.'
        ];
    }

    public function attributes()
    {
        return [
            'search' => 'Término de búsqueda',
            'perPage' => 'Número de registros por página',
            'form_type_id' => 'Tipo de formulario',
            'form_id' => 'Formulario'
        ];
    }
}
