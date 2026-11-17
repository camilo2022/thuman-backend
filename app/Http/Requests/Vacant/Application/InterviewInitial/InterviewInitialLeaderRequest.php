<?php

namespace App\Http\Requests\Vacant\Application\InterviewInitial;

use App\Models\InterviewInitial;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class InterviewInitialLeaderRequest extends FormRequest
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
            'leader_observation' => ['required', 'string', 'max:2000'],
            'leader_score' => ['required', 'integer', 'between:1,5'],
            'leader_id' => ['required', 'exists:people,id'],
            'leader_status' => ['required', 'in:' . implode(',', [InterviewInitial::APPROVED, InterviewInitial::REJECTED])],
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
        ];
    }

    public function attributes()
    {
        return [
            'leader_observation' => 'Observacion Lider',
            'leader_score' => 'Calificación Lider',
            'leader_id' => 'Lider',
            'leader_status' => 'Estado Lider',
            'status' => 'Estado'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $interviewInitial = InterviewInitial::find($this->route('interviewInitialId'));
            if (!$interviewInitial) {
                $validator->errors()->add('status', 'Entrevista inicial no encontrada.');
                return;
            }

            if (!in_array($interviewInitial->status, [InterviewInitial::COMPLETED], true)) {
                $validator->errors()->add('status', 'Los estados permitidos para realizar esta acción son: '. implode(', ', [InterviewInitial::COMPLETED]) . '.');
            }
        });
    }
}
