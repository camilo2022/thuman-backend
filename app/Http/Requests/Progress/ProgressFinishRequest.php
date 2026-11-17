<?php

namespace App\Http\Requests\Progress;

use App\Models\Progress;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProgressFinishRequest extends FormRequest
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
            'answers' => ['required', 'array'],
            'result' => ['required', 'in:' . implode(',', [Progress::APPROVED, Progress::REJECTED])]
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'array' => 'Debe ser una lista de datos.'
        ];
    }

    public function attributes()
    {
        return [
            'answers' => 'Respuestas',
            'status' => 'Estado',
            'result' => 'Resultado',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $progress = Progress::find($this->route('progressId'));
            if (!$progress) {
                $validator->errors()->add('status', 'Progeso no encontrada.');
                return;
            }

            if (!in_array($progress->status, [Progress::INPROGRESS], true)) {
                $validator->errors()->add('status', 'Los estados permitidos para realizar esta acción son: '. implode(', ', [Progress::INPROGRESS]) . '.');
            }

            if (!in_array($progress->result, [Progress::PENDING], true)) {
                $validator->errors()->add('result', 'Los estados permitidos para realizar esta acción son: '. implode(', ', [Progress::PENDING]) . '.');
            }
        });
    }
}
