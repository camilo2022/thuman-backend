<?php

namespace App\Http\Requests\Termination;

use App\Models\Reason;
use App\Models\TerminationType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TerminationUpdateRequest extends FormRequest
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
            'person_id' => ['required', 'exists:people,id', 'exists:employees,person_id,deleted_at,NULL'],
            'model_id' => ['required',
                'exists:subitems,id,item_id,' . match ($this->input('model_type')) {
                    TerminationType::class  => '47',
                    Reason::class => '48',
                    default => '47'
                }],
            'model_type' => ['required', 'in:' . implode(',', [TerminationType::class, Reason::class])],
            'reason' => ['required', 'string', 'max:5000'],
            'file' => ['nullable', 'array'],
            'file.file' => ['nullable', 'file', 'mimes:pdf', 'max:2048'],
            'file.file_type_id' => ['nullable', 'exists:subitems,id,item_id,16', 'in:100'],
            'file.file_subtype_id' => ['nullable', 'exists:subitems,id,item_id,17', 'in:127'],
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'exists' => 'No existe en la base de datos.',
            'string' => 'Debe ser una cadena de caracteres.',
            'max' => 'No debe exceder los :max caracteres.',
            'date' => 'Debe ser una fecha válida.',
            'date_format' => 'Formato inválido. Permitido :format.',
            'after' => 'Debe ser una fecha posterior a :date.',
            'file' => 'Debe ser un archivo.',
            'mimes' => 'Extensión inválida. Permitidas :values.',
            'file.file.max' => 'No debe exceder los :max KB.'
        ];
    }

    public function attributes()
    {
        return [
            'person_id' => 'Persona',
            'model_type' => 'Tipo de modelo',
            'model_id' => match ($this->input('model_type')) {
                TerminationType::class => 'Tipo de terminación de contrato',
                Reason::class => 'Motivo de la terminación del contrato',
                default => 'Modelo',
            },
            'date' => 'Fecha de finalización',
            'reason' => 'Motivo',
            'file' => 'Configuración anexo',
            'file.file' => 'Anexo',
            'file.file_type_id' => 'Tipo de archivo',
            'file.file_subtype_id' => 'Subtipo de archivo',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            if ($this->input('model_type') === TerminationType::class) {

                $terminationType = TerminationType::find($this->integer('model_id'));

                if (!$terminationType) return;

                if ($terminationType->settings?->reason_required) {
                    $validator->errors()->add('model_type', 'Requiere un motivo.');
                }

                if($terminationType->settings?->file_required) {
                    if(!$this->filled('file')) {
                        $validator->errors()->add('file', 'Es requerido.');
                        return;
                    }

                    if (!$this->hasFile('file.file')) {
                        $validator->errors()->add('file.file', 'Es requerido.');
                    }

                    if (!$this->filled('file.file_type_id')) {
                        $validator->errors()->add('file.file_type_id', 'Es requerido.');
                    }

                    if (!$this->filled('file.file_subtype_id')) {
                        $validator->errors()->add('file.file_subtype_id', 'Es requerido.');
                    }
                }
            }

            if ($this->input('model_type') === Reason::class) {

                $reason = Reason::with('termination_type')->find($this->integer('model_id'));

                if (!$reason) return;

                if (!$reason->termination_type->first()?->settings?->reason_required) {
                    $validator->errors()->add('model_type', 'No requiere motivo.');
                }
            }
        });
    }
}
