<?php

namespace App\Http\Requests\Vacant\Application;

use App\Models\Application;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ApplicationFinalistRequest extends FormRequest
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
        return [];
    }

    public function messages()
    {
        return [];
    }

    public function attributes()
    {
        return [
            'status' => 'Estado'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $application = Application::find($this->route('applicationId'));
            if (!$application) {
                $validator->errors()->add('status', 'Aplicación no encontrada.');
                return;
            }

            if (!in_array($application->status, [Application::TEST], true)) {
                $validator->errors()->add('status', 'Los estados permitidos para realizar esta acción son: '. implode(', ', [Application::TEST]) . '.');
            }
        });
    }
}
