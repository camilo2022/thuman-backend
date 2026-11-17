<?php

namespace App\Http\Requests\Enterprise\Entity;

use App\Models\DocumentType;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class EntityStoreRequest extends FormRequest
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
            'enterprise_id' => ['required', 'exists:enterprises,id'],
            'entity_type_id' => ['required', 'exists:subitems,id,item_id,30'],
            'name' => ['required', 'string', 'max:80'],
            'country_id' => ['required', 'exists:countries,id'],
            'document_type_id' => ['required', 'exists:subitems,id,item_id,4'],
            'document_number' => ['required', 'string', 'max:30', 'unique:entities,document_number'],
            'person_id' => ['required', 'exists:people,id'],
            'settings' => ['required', 'array'],
            'settings.vacations' => ['required', 'array', 'min:1'],
            'settings.vacations.*' => ['required', 'array'],
            'settings.vacations.*.period' => ['required', 'integer', 'min:2000', 'max:' . Carbon::now()->format('Y'), 'distinct'],
            'settings.vacations.*.legal_days' => ['required', 'integer', 'min:1'],
            'settings.vacations.*.days' => ['required', 'array', 'min:1'],
            'settings.vacations.*.days.*' => ['required', 'integer', 'between:1,7']
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'exists' => 'No existe en la base de datos.',
            'string' => 'Debe ser una cadena de caracteres.',
            'unique' => 'Ya ha sido tomado.',
            'file' => 'Debe ser un archivo.',
            'max' => 'No debe exceder los :max caracteres.',
            'min' => 'Debe tener al menos :min elementos.',
            'between' => 'Debe estar entre :min y :max.',
            'distinct' => 'Ya ha sido tomado en otra validación.',
            'array' => 'Debe ser una lista de datos.',
            'integer' => 'Debe ser un valor numérico entero.',
            'mimes' => 'Extensión inválida. Permitidas :mimes.',
            'settings.vacations.*.period' => 'Debe tener al menos :min.',
            'settings.vacations.*.period' => 'No debe exceder los :max.',
            'settings.vacations.*.legal_days' => 'Debe tener al menos :min.',
            'settings.vacations.*.business_days.*.between' => 'Los días hábiles deben estar entre lunes (1) y domingo (7).'
        ];
    }

    public function attributes()
    {
        return [
            'enterprise_id' => 'Empresa',
            'entity_type_id' => 'Tipo de entidad',
            'name' => 'Razon social',
            'country_id' => 'Pais',
            'document_type_id' => 'Tipo de documento',
            'document_number' => 'Número de documento',
            'person_id' => 'Representante legal',
            'settings' => 'Configuración',
            'settings.vacations' => 'Configuracion de vacaciones',
            'settings.vacations.*' => 'Configuración de vacaciones por período',
            'settings.vacations.*.period' => 'Período (año)',
            'settings.vacations.*.legal_days' => 'Días legales de vacaciones',
            'settings.vacations.*.days' => 'Días habiles de vacaciones de la entidad',
            'settings.vacations.*.business_days.*' => 'Día hábil',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $documentType = DocumentType::find($this->input('document_type_id'));

            if (!$documentType) {
                $validator->errors()->add('document_number', 'No se ha podido validar.');
                return;
            }

            $validation = collect($documentType->settings?->validations ?? [])
                ->where('country_id', $this->input('country_id'))->first();

            if (!$validation) {
                $validator->errors()->add('document_number', 'No hay formato de validación de documento para el país seleccionado.');
                return;
            }

            $regex = stripslashes($validation->regex ?? '');
            $message = $validation->message ?? 'El formato no es válido.';

            if ($regex === '' || @preg_match($regex, '') === false) {
                $validator->errors()->add('document_number', 'La regla de validación configurada no es válida.');
                return;
            }

            if (!preg_match($regex, $this->input('document_number', ''))) {
                $validator->errors()->add('document_number', $message);
            }
        });
    }
}
