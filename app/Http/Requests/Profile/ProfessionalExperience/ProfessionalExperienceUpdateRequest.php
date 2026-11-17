<?php

namespace App\Http\Requests\Profile\ProfessionalExperience;

use App\Models\Country;
use App\Models\ProfessionalExperience;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProfessionalExperienceUpdateRequest extends FormRequest
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
        $professionalExperience = ProfessionalExperience::with('file')->find($this->id);
        $hasExistingFile = $professionalExperience && $professionalExperience->file;

        $fileIsRequired = !$hasExistingFile;
        return [
            'profile_id' => ['required', 'exists:profiles,id'],
            'company_name' => ['required', 'string', 'max:255'],
            'supervisor_name' => ['required', 'string', 'max:255'],
            'supervisor_dialing_country_id' => ['required', 'exists:countries,id'],
            'supervisor_phone_number' => ['required', 'string', 'max:30'],
            'city_id' => ['required', 'exists:cities,id'],
            'company_address' => ['required', 'string', 'max:75'],
            'start_date' => ['required', 'date', 'date_format:Y-m-d'],
            'end_date' => ['nullable', 'date', 'date_format:Y-m-d', 'after_or_equal:start_date'],
            'position' => ['required', 'string', 'max:50'],
            'work_arrangement_id' => ['required', 'exists:subitems,id,item_id,3'],
            'position_description' => ['required', 'string', 'max:500'],
            'file' => ['nullable', 'array'],
            'file.file' => [($fileIsRequired ? 'required' : 'sometimes'), $this->filled('end_date') ? 'required' : 'sometimes', 'file', 'mimes:pdf', 'max:2048'],
            'file.file_type_id' => ['required_with:file.file', 'exists:subitems,id,item_id,16', 'in:100'],
            'file.file_subtype_id' => ['required_with:file.file', 'exists:subitems,id,item_id,17', 'in:123']
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'required_with' => 'Es requerido cuando :values está presente.',
            'exists' => 'No existe en la base de datos.',
            'string' => 'Debe ser una cadena de caracteres.',
            'max' => 'No debe exceder los :max caracteres.',
            'date' => 'Debe ser una fecha válida.',
            'date_format' => 'Formato inválido. Permitido :format.',
            'array' => 'Debe ser una lista de datos.',
            'mimes' => 'Extensión inválida. Permitidas :values.',
            'file' => 'Debe ser un archivo.',
        ];
    }

    public function attributes()
    {
        return [
            'profile_id' => 'Perfil',
            'company_name' => 'Empresa',
            'supervisor_name' => 'Jefe inmediato',
            'supervisor_dialing_country_id' => 'Codigo de pais',
            'supervisor_phone_number' => 'Número de teléfono',
            'city_id' => 'Ciudad',
            'company_address' => 'Dirección',
            'start_date' => 'Fecha de inicio',
            'end_date' => 'Fecha de finalización',
            'position' => 'Cargo',
            'work_arrangement_id' => 'Modalidad de trabajo',
            'position_description' => 'Descripción de labores',
            'file' => 'Configuración certificado',
            'file.file' => 'Certificado',
            'file.file_type_id' => 'Tipo de archivo',
            'file.file_subtype_id' => 'Subtipo de archivo',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $country = Country::find($this->input('supervisor_dialing_country_id'));

            if (!$country) {
                $validator->errors()->add('supervisor_phone_number', 'No se ha podido validar.');
            } else {
                $validations = $country->settings?->validations ?? [];

                if (!is_array($validations) || empty($validations)) {
                    $validator->errors()->add('supervisor_phone_number', 'No hay formatos de validación de teléfono configurados para el país seleccionado.');
                } else {
                    $phone_number = $this->input('supervisor_phone_number');
                    $passed = false;
                    $messages = [];

                    foreach ($validations as $rule) {
                        $regex = stripslashes($rule->regex ?? '');
                        $message = $rule->message ?? 'El número de teléfono no es válido.';

                        if (preg_match($regex, $phone_number)) {
                            $passed = true;
                            break;
                        }

                        $messages[] = $message;
                    }

                    if (!$passed) {
                        foreach ($messages as $msg) {
                            $validator->errors()->add('supervisor_phone_number', $msg);
                        }
                    }
                }
            }
        });
    }
}
