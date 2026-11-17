<?php

namespace App\Http\Requests\Vacant\Application\InterviewVideo;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class InterviewVideoFindRequest extends FormRequest
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
            'application_id' => [$this->route('interviewVideoId') ? 'nullable' : 'required', 'exists:applications,id']
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'exists' => 'No existe en la base de datos.',
        ];
    }

    public function attributes()
    {
        return [
            'application_id' => 'Aplicación'
        ];
    }
}
