<?php

namespace App\Http\Requests\Vacant\Application\CriminalRecord;

use App\Models\Application;
use App\Models\CriminalRecord;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CriminalRecordStoreRequest extends FormRequest
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
            'application_id' => ['required', 'exists:applications,id', 'unique:criminal_records,application_id'],
            'observation' => ['required', 'string', 'max:2000'],
            'status' => ['required', 'in:' . implode(',', CriminalRecord::statuses())],
            'file' => ['required', 'array'],
            'file.file' => ['required', 'file', 'mimes:pdf'],
            'file.file_type_id' => ['required', 'exists:subitems,id,item_id,16', 'in:100'],
            'file.file_subtype_id' => ['required', 'exists:subitems,id,item_id,17', 'in:129']
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'exists' => 'No existe en la base de datos.',
            'unique' => 'Ya ha sido tomado.',
            'string' => 'Debe ser una cadena de caracteres.',
            'max' => 'No debe exceder los :max caracteres.',
            'array' => 'Debe ser una lista de datos.',
            'mimes' => 'Extensión inválida. Permitidas :values.',
            'file' => 'Debe ser un archivo.',
        ];
    }

    public function attributes()
    {
        return [
            'application_id' => 'Aplicación',
            'observation' => 'Observación',
            'status' => 'Estado',
            'file' => 'Configuración archivo',
            'file.file' => 'Archivo',
            'file.file_type_id' => 'Tipo de archivo',
            'file.file_subtype_id' => 'Subtipo de archivo',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $application = Application::find($this->integer('application_id'));
            if (!$application) {
                $validator->errors()->add('application_id', 'Aplicación no encontrada.');
                return;
            }

            if (!in_array($application->status, [Application::REVIEW, Application::PENDING], true)) {
                $validator->errors()->add('application_id', 'Los estados permitidos para realizar esta acción son: '. implode(', ', [Application::REVIEW, Application::PENDING]) . '.');
            }
        });
    }
}
