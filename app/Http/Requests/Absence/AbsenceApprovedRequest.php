<?php

namespace App\Http\Requests\Absence;

use App\Models\Absence;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AbsenceApprovedRequest extends FormRequest
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
            $absence = Absence::find($this->route('absenceId'));
            if (!$absence) {
                $validator->errors()->add('status', 'Permiso no encontrada.');
                return;
            }

            if (!in_array($absence->status, [Absence::REVIEW], true)) {
                $validator->errors()->add('status', 'Los estados permitidos para realizar esta acción son: '. implode(', ', [Absence::REVIEW]) . '.');
            }
        });
    }
}
