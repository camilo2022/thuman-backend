<?php

namespace App\Http\Requests\Induction;

use App\Models\Area;
use App\Models\Enterprise;
use App\Models\Entity;
use App\Models\Headquarter;
use App\Models\Position;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class InductionStoreRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255', 'unique:inductions,name'],
            'description' => ['required', 'string', 'max:500'],
            'assignable_type' => ['nullable', 'in:' . implode(',', [Enterprise::class, Entity::class, Headquarter::class])],
            'assignable_id' => ['required_with:assignable_type',
                match ($this->input('assignable_type')) {
                    Enterprise::class  => 'exists:enterprises,id',
                    Entity::class => 'exists:entities,id',
                    Headquarter::class => 'exists:headquarters,id',
                    default => 'nullable'
                }],
            'model_type' => ['nullable', 'in:' . implode(',', [Area::class, Position::class])],
            'model_id' => ['required_with:model_type',
                match ($this->input('model_type')) {
                    Area::class => 'exists:areas,id',
                    Position::class => 'exists:positions,id',
                    default => 'nullable'
                }]
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'required_with' => 'Es requerido cuando :values está presente.',
            'string' => 'Debe ser una cadena de caracteres.',
            'max' => 'No debe exceder los :max caracteres.',
            'exists' => 'No existe en la base de datos.',
            'unique' => 'Ya ha sido tomado.',
            'in' => 'Valor inválido. Permitidos: :values.',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Nombre',
            'description' => 'Descripción',
            'assignable_type' => 'Tipo asignable',
            'assignable_id' => match ($this->input('assignable_type')) {
                Enterprise::class  => 'Empresa',
                Entity::class => 'Entidad',
                Headquarter::class => 'Sede',
                default => 'Registro asignable',
            },
            'model_type' => 'Tipo de modelo',
            'model_id' => match ($this->input('model_type')) {
                Area::class => 'Área',
                Position::class => 'Perfil de cargo',
                default => 'Modelo',
            },
        ];
    }
}
