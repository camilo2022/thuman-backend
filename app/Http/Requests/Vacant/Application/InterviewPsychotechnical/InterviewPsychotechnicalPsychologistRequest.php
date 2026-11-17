<?php

namespace App\Http\Requests\Vacant\Application\InterviewPsychotechnical;

use App\Models\InterviewPsychotechnical;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class InterviewPsychotechnicalPsychologistRequest extends FormRequest
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
            'psychologist_observation' => ['required', 'string', 'max:2000'],
            'psychologist_score' => ['required', 'integer', 'between:1,5'],
            'psychologist_id' => ['required', 'exists:people,id'],
            'psychologist_status' => ['required', 'in:' . implode(',', [InterviewPsychotechnical::APPROVED, InterviewPsychotechnical::REJECTED])],
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'string' => 'Debe ser una cadena de caracteres.',
            'integer' => 'Debe ser un valor numérico entero.',
            'exists' => 'No existe en la base de datos.',
        ];
    }

    public function attributes()
    {
        return [
            'psychologist_observation' => 'Observacion Psicólogo',
            'psychologist_score' => 'Calificación Psicólogo',
            'psychologist_id' => 'Psicólogo',
            'psychologist_status' => 'Estado Psicólogo',
            'status' => 'Estado'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $interviewPsychotechnical = InterviewPsychotechnical::find($this->route('interviewPsychotechnicalId'));
            if (!$interviewPsychotechnical) {
                $validator->errors()->add('status', 'Entrevista psicotécnica no encontrada.');
                return;
            }

            if (!in_array($interviewPsychotechnical->status, [InterviewPsychotechnical::COMPLETED], true)) {
                $validator->errors()->add('status', 'Los estados permitidos para realizar esta acción son: '. implode(', ', [InterviewPsychotechnical::COMPLETED]) . '.');
            }
        });
    }
}
