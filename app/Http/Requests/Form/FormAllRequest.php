<?php

namespace App\Http\Requests\Form;

use App\Models\Area;
use App\Models\Enterprise;
use App\Models\Entity;
use App\Models\Headquarter;
use App\Models\Position;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class FormAllRequest extends FormRequest
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

    protected function prepareForValidation()
    {
        $this->merge([
            'with_trashed' => filter_var($this->input('with_trashed'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE)
        ]);
    }

    public function rules()
    {

        $typeAssig = $this->input('assignable_type');
        $typeMod = $this->input('model_type');
        return [
            'search' => ['nullable', 'string', 'max:100'],
            'perPage' => ['nullable', 'numeric'],
            'with_trashed' => ['nullable', 'boolean'],
            'form_type_id' => ['nullable', 'exists:subitems,id,item_id,18'],
            'assignable_type' => ['nullable', 'in:' . implode(',', [Enterprise::class, Entity::class, Headquarter::class])],
            'assignable_id' => [
                'nullable',
                function ($attribute, $value, $fail) use ($typeAssig) {
                    if (!$typeAssig) {
                        return;
                    }

                    $exists = false;

                    if ($typeAssig === Enterprise::class) {
                        $exists = Enterprise::where('id', $value)->exists();
                    } elseif ($typeAssig === Entity::class) {
                        $exists = Entity::where('id', $value)->exists();
                    } elseif ($typeAssig === Headquarter::class) {
                        $exists = Headquarter::where('id', $value)->exists();
                    }

                    if (!$exists) {
                        $fail('El assignable_id no existe para el assignable_type enviado.');
                    }
                }
            ],
            'model_type' => ['nullable', 'in:' . implode(',', [Area::class, Position::class])],
            'model_id' => [
                'nullable',
                function ($attribute, $value, $fail) use ($typeMod) {
                    if (!$typeMod) {
                        return;
                    }

                    $exists = false;

                    if ($typeMod === Area::class) {
                        $exists = Area::where('id', $value)->exists();
                    } elseif ($typeMod === Position::class) {
                        $exists = Position::where('id', $value)->exists();
                    }

                    if (!$exists) {
                        $fail('El model_id no existe para el model_type enviado.');
                    }
                }
            ],
        ];
    }

    public function messages()
    {
        return [
            'required_with' => 'Es requerido cuando :values está presente.',
            'string' => 'Debe ser una cadena de caracteres.',
            'boolean' => 'Deber ser requerido o no requerido.',
            'numeric' => 'Debe ser un valor numérico.',
            'exists' => 'No existe en la base de datos.'
        ];
    }

    public function attributes()
    {
        return [
            'search' => 'Término de búsqueda',
            'perPage' => 'Número de registros por página',
            'with_trashed' => 'Registros inactivos',
            'form_type_id' => 'Tipo de formulario',
            'entity_id' => 'Entidad internacional',
            'position_id' => 'Cargo',
        ];
    }
}
