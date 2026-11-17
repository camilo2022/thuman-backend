<?php

namespace App\Http\Requests\Contract\Policy;

use App\Models\Addendum;
use App\Models\Contract;
use App\Models\Policy;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class PolicyUpdateRequest extends FormRequest
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
            'model_type' => ['required', 'string', 'in:' . implode(',', [Policy::class, Contract::class, Addendum::class])],
            'model_id' => ['required',
                'exists:' . match ($this->input('model_type')) {
                    Policy::class => 'policies',
                    Contract::class => 'contracts',
                    Addendum::class => 'addendums',
                    default => 'contracts'
                } . ',id'],
            'code' => ['required', 'string', 'min:8', 'max:50'/*, 'unique:policies,code,' . $this->input('policyId') . ',id'*/],
            'value' => ['required', 'numeric', 'min:0'],
            'insured_value' => ['required', 'numeric', 'min:0'],
            'start_date' => ['required', 'date', 'date_format:Y-m-d'],
            'end_date' => ['nullable', 'date', 'date_format:Y-m-d', 'after_or_equal:start_date'],
            'file' => ['required', 'array'],
            'file.file' => ['nullable', 'file', 'mimetypes:application/pdf', 'max:2048'],
            'file.file_type_id' => ['required_with:file.file', 'exists:subitems,id,item_id,16', 'in:100'],
            'file.file_subtype_id' => ['required_with:file.file', 'exists:subitems,id,item_id,17', 'in:129'],
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'required_with' => 'Es requerido cuando :values está presente.',
            'string' => 'Debe ser una cadena de caracteres.',
            'min' => 'Debe ser mínimo :min caracteres.',
            'max' => 'No debe exceder los :max caracteres.',
            'unique' => 'Ya ha sido tomado.',
            'numeric' => 'Debe ser un valor numérico.',
            'exists' => 'No existe en la base de datos.',
            'after_or_equal' => 'Debe ser una fecha posterior o igual a hoy.',
            'date' => 'Debe ser una fecha válida.',
            'date_format' => 'Formato inválido. Permitido :format.',
            'in' => 'Valor inválido. Permitidos: :values.',
            'file.file.max' => 'No debe exceder los :max KB.',
            'array' => 'Debe ser una lista de datos.',
            'mimetypes' => 'Extensión inválida. Permitidas :values.',
            'file' => 'Debe ser un archivo.',
        ];
    }

    public function attributes()
    {
        return [
            'model_type' => 'Tipo de modelo',
            'model_id' => match ($this->input('model_type')) {
                Policy::class => 'Póliza',
                Contract::class => 'Contrato',
                Addendum::class => 'Otro sí',
                default => 'Modelo',
            },
            'code' => 'Código',
            'value' => 'Valor',
            'insured_value' => 'Valor asegurado',
            'start_date' => 'Fecha de inicio',
            'end_date' => 'Fecha de finalización',
            'file' => 'Configuración certificado póliza',
            'file.file' => 'Certificado póliza',
            'file.file_type_id' => 'Tipo de archivo',
            'file.file_subtype_id' => 'Subtipo de archivo',
            'status' => 'Estado'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $policy = Policy::find($this->route('policyId'));
            if (!$policy) {
                $validator->errors()->add('status', 'Póliza no encontrada.');
                return;
            }

            if (!in_array($policy->status, [Policy::REVIEW], true)) {
                $validator->errors()->add('legal_represstatusentative_status', 'Los estados permitidos para realizar esta acción son: '. implode(', ', [Policy::REVIEW]) . '.');
            }
        });
    }
}
