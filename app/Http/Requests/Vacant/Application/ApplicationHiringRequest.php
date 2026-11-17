<?php

namespace App\Http\Requests\Vacant\Application;

use App\Models\Application;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ApplicationHiringRequest extends FormRequest
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
            'salary' => ['required', 'numeric', 'min:0'],
            'observation' => ['required', 'string', 'max:500']
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'string' => 'Debe ser una cadena de caracteres.',
            'max' => 'No debe exceder los :max caracteres.',
            'min' => 'Debe ser mínimo :min.',
            'numeric' => 'Debe ser un valor numérico.'
        ];
    }

    public function attributes()
    {
        return [
            'salary' => 'Salario',
            'observation' => 'Observacion',
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

            if (!in_array($application->status, [Application::FINALIST], true)) {
                $validator->errors()->add('status', 'Los estados permitidos para realizar esta acción son: '. implode(', ', [Application::FINALIST]) . '.');
            }
        });
    }
}
