<?php

namespace App\Http\Requests\Progress;

use App\Models\Progress;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProgressStartRequest extends FormRequest
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
            'person_id' => ['required', 'exists:people,id', 'exists:employees,person_id,deleted_at,NULL', 'unique:progress,person_id,NULL,id,step_id,' . $this->integer('step_id') . ',status,' . Progress::INPROGRESS],
            'step_id' => ['required', 'exists:steps,id,deleted_at,NULL'],
            'questions' => ['required', 'array'],
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'unique' => 'Ya ha sido tomado.',
            'exists' => 'No existe en la base de datos.',
            'array' => 'Debe ser una lista de datos.'
        ];
    }

    public function attributes()
    {
        return [
            'person_id' => 'Persona',
            'step_id' => 'Paso',
            'questions' => 'Preguntas'
        ];
    }
}
