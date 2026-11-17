<?php

namespace App\Http\Requests\Severance;

use App\Models\SeveranceType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SeveranceStoreRequest extends FormRequest
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

    protected function prepareForValidation()
    {
        $this->merge([
            'reason' => $this->cleanHtml($this->input('reason'))
        ]);
    }

    private function cleanHtml(?string $value): ?string
    {
        if (!$value) return $value;

        return trim(preg_replace(['/<p><br\s*\/?><\/p>/i', '/<p><\/p>/i', '/<br\s*\/?>/i', '/&nbsp;/i'], '', $value));
    }

    public function rules()
    {
        $severanceType = SeveranceType::find($this->input('severance_type_id'));

        return [
            'person_id' => ['required', 'exists:people,id', 'exists:employees,person_id,deleted_at,NULL'],
            'severance_type_id' => ['required', 'exists:subitems,id,item_id,42'],
            'reason' => ['required', 'string', 'min:10', 'max:2000'],
            'file' => [$severanceType->settings?->file_required ? 'required' : 'nullable', 'array'],
            'file.file' => [$severanceType->settings?->file_required ? 'required' : 'nullable', 'file', 'mimes:pdf', 'max:2048'],
            'file.file_type_id' => [$severanceType->settings?->file_required ? 'required' : 'nullable', 'exists:subitems,id,item_id,16', 'in:100'],
            'file.file_subtype_id' => [$severanceType->settings?->file_required ? 'required' : 'nullable', 'exists:subitems,id,item_id,17', 'in:127'],
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'exists' => 'No existe en la base de datos.',
            'string' => 'Debe ser una cadena de caracteres.',
            'max' => 'No debe exceder los :max caracteres.',
            'min' => 'Debe ser mínimo :min.',
            'file' => 'Debe ser un archivo.',
            'mimes' => 'Extensión inválida. Permitidas :values.',
            'file.file.max' => 'No debe exceder los :max KB.'
        ];
    }

    public function attributes()
    {
        return [
            'person_id' => 'Persona',
            'severance_type_id' => 'Tipo de permiso',
            'start_date' => 'Fecha inicio',
            'end_date' => 'Fecha fin',
            'reason' => 'Motivo',
            'compensation' => 'Compensación',
            'file' => 'Configuración anexo',
            'file.file' => 'Anexo',
            'file.file_type_id' => 'Tipo de archivo',
            'file.file_subtype_id' => 'Subtipo de archivo',
        ];
    }
}
