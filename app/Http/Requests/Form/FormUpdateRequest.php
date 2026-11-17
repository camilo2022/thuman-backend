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

class FormUpdateRequest extends FormRequest
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
            'form_type_id' => ['required', 'exists:subitems,id,item_id,18'],
            'assignable_type' => [$formType?->settings?->validations['assignable_type'] ?? 'nullable'],
            'assignable_id' => [
                'required_with:assignable_type',
                match ($this->input('assignable_type')) {
                    Enterprise::class  => 'exists:enterprises,id',
                    Entity::class => 'exists:entities,id',
                    Headquarter::class => 'exists:headquarters,id',
                    default => 'nullable'
                }
            ],
            'model_type' => [$formType?->settings?->validations['model_type'] ?? 'nullable'],
            'model_id' => [
                'required_with:model_type',
                match ($this->input('model_type')) {
                    Area::class => 'exists:areas,id',
                    Position::class => 'exists:positions,id',
                    default => 'nullable'
                }
            ],
            'questions' => ['required', 'array'],
            'questions.title' => ['required', 'string'],
            'questions.description' => ['sometimes', 'string'],
            'questions.pages' => ['required', 'array', 'min:1'],
            'questions.pages.*' => ['required', 'array'],
            'questions.pages.*.elements' => ['required', 'array', 'min:1']
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'exists' => 'No existe en la base de datos.',
            'string' => 'Debe ser una cadena de caracteres.',
            'array' => 'Debe ser una lista de datos.',
            'questions.pages.min' => 'Debe existir al menos una página.',
            'questions.pages.*.elements.min' => 'Cada página debe contener al menos una pregunta.',
        ];
    }

    public function attributes()
    {
        return [
            'form_type_id' => 'Tipo de formulario',
            'entity_id' => 'Entidad internacional',
            'position_id' => 'Cargo',
            'questions' => 'Preguntas',
            'questions.title' => 'Titulo',
            'questions.description' => 'Descripción',
            'questions.pages' => 'Paginas',
            'questions.pages.*' => 'Página',
            'questions.pages.*.elements' => 'Preguntas de la página',
            'questions.pages.*.elements.*' => 'Pregunta',
        ];
    }
}
