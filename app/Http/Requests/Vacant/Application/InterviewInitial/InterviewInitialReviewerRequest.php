<?php

namespace App\Http\Requests\Vacant\Application\InterviewInitial;

use App\Models\InterviewInitial;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class InterviewInitialReviewerRequest extends FormRequest
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
            'salary_min' => ['required', 'numeric', 'min:0'],
            'salary_max' => ['nullable', 'numeric', 'gte:salary_min'],
            'form_id' => ['nullable', 'exists:forms,id,form_type_id,138'],
            'reviewer_observation' => ['required', 'string', 'max:2000'],
            'reviewer_score' => ['required', 'integer', 'between:1,5'],
            'reviewer_id' => ['required', 'exists:people,id'],
            'reviewer_status' => ['required', 'in:' . implode(',', [InterviewInitial::APPROVED, InterviewInitial::REJECTED])],
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'string' => 'Debe ser una cadena de caracteres.',
            'max' => 'No debe exceder los :max caracteres.',
            'min' => 'Debe ser mínimo :min.',
            'gte' => 'Debe ser mayor o igual que :value.',
            'integer' => 'Debe ser un valor numérico entero.',
            'numeric' => 'Debe ser un valor numérico.',
            'exists' => 'No existe en la base de datos.',
        ];
    }

    public function attributes()
    {
        return [
            'salary_min' => 'Salario mínimo',
            'salary_max' => 'Salario máximo',
            'reviewer_observation' => 'Observacion Revisor',
            'reviewer_score' => 'Calificación Revisor',
            'reviewer_id' => 'Revisor',
            'reviewer_status' => 'Estado Revisor',
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
