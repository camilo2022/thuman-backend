<?php

namespace App\Http\Requests\Progress;

use App\Models\Progress;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProgressFindRequest extends FormRequest
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
            'person_id' => [$this->route('progressId') ? 'nullable' : 'required', 'exists:people,id'],
            'step_id' => [$this->route('progressId') ? 'nullable' : 'required', 'exists:steps,id'],
            'status' => [$this->route('progressId') ? 'nullable' : 'required', 'in:' . implode(',', Progress::statuses())]
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'exists' => 'No existe en la base de datos.'
        ];
    }

    public function attributes()
    {
        return [
            'person_id' => 'Persona',
            'step_id' => 'Paso',
            'status' => 'Estado'
        ];
    }
}
