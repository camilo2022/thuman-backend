<?php

namespace App\Http\Requests\Vacant\Application\ExamPolygraph;

use App\Models\ExamPolygraph;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ExamPolygraphReviewerRequest extends FormRequest
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
            'result' => ['required', 'in:' . implode(',', [ExamPolygraph::APT, ExamPolygraph::NOT_APT])],
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
            $vacant = ExamPolygraph::find($this->route('examPolygraphId'));
            if (!$vacant) {
                $validator->errors()->add('status', 'Entrevista inicial no encontrada.');
                return;
            }

            if (!in_array($vacant->status, [ExamPolygraph::COMPLETED], true)) {
                $validator->errors()->add('status', 'Los estados permitidos para realizar esta acción son: '. implode(', ', [ExamPolygraph::COMPLETED]) . '.');
            }
        });
    }
}
