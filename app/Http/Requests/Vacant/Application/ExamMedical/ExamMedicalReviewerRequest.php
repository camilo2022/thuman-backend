<?php

namespace App\Http\Requests\Vacant\Application\ExamMedical;

use App\Models\ExamMedical;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ExamMedicalReviewerRequest extends FormRequest
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
            'description' => ['required', 'string', 'max:2000'],
            'result' => ['required', 'in:' . implode(',', [ExamMedical::APT, ExamMedical::APT_WITH_RECOMMENDATIONS, ExamMedical::NOT_APT, ExamMedical::POSTPONED])],
            'epidemiological_program_id' => ['required_if:result,"' . ExamMedical::APT_WITH_RECOMMENDATIONS . '"', 'exists:subitems,id,item_id,32'],
            'file' => ['required', 'array'],
            'file.file' => ['required', 'file', 'mimes:pdf', 'max:2048'],
            'file.file_type_id' => ['required', 'exists:subitems,id,item_id,16', 'in:96'],
            'file.file_subtype_id' => ['required', 'exists:subitems,id,item_id,17', 'in:105']
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'required_if' => 'Es requerido.',
            'string' => 'Debe ser una cadena de caracteres.',
            'max' => 'No debe exceder los :max caracteres.',
            'integer' => 'Debe ser un valor numérico entero.',
            'exists' => 'No existe en la base de datos.',
            'file' => 'Debe ser un archivo.',
            'array' => 'Debe ser una lista de datos.',
        ];
    }

    public function attributes()
    {
        return [
            'description' => 'Descripción',
            'result' => 'Resultado',
            'epidemiological_program_id' => 'Programa de vigilancia epidemológica',
            'status' => 'Estado',
            'file' => 'Configuración documento examenes medicos',
            'file.file' => 'Examenes medicos',
            'file.file_type_id' => 'Tipo de archivo',
            'file.file_subtype_id' => 'Subtipo de archivo',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $examMedical = ExamMedical::find($this->route('examMedicalId'));
            if (!$examMedical) {
                $validator->errors()->add('status', 'Examen médico no encontrada.');
                return;
            }

            if (!in_array($examMedical->status, [ExamMedical::COMPLETED], true)) {
                $validator->errors()->add('status', 'Los estados permitidos para realizar esta acción son: '. implode(', ', [ExamMedical::COMPLETED]) . '.');
            }
        });
    }
}
