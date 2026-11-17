<?php

namespace App\Http\Requests\Person;

use App\Models\City;
use App\Models\Country;
use App\Models\DocumentType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class PersonUpdateRequest extends FormRequest
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
            'first_name' => ['required', 'string', 'min:1', 'max:25', 'regex:/^[\pL\s]+$/u'],
            'second_name' => ['nullable', 'string', 'min:1', 'max:25', 'regex:/^[\pL\s]+$/u'],
            'first_last_name' => ['required', 'string', 'min:3', 'max:25', 'regex:/^[\pL\s]+$/u'],
            'second_last_name' => ['nullable', 'string', 'min:3', 'max:25', 'regex:/^[\pL\s]+$/u'],
            'birth_date' => ['sometimes', 'date', 'date_format:Y-m-d'],
            'birth_city_id' => ['nullable', 'exists:cities,id'],
            'document_number' => ['required', 'string', 'max:30', 'unique:people,document_number,' . $this->route('personId') . ',id'],
            'document_issue_city_id' => ['required', 'exists:cities,id'],
            'document_issue_date' => ['sometimes', 'date', 'date_format:Y-m-d', 'after:birth_date'],
            'city_id' => ['sometimes', 'exists:cities,id'],
            'address' => ['sometimes', 'string', 'max:75'],
            'neighborhood' => ['sometimes', 'string', 'max:50'],
            'dialing_country_id' => ['required', 'exists:countries,id'],
            'phone_number' => ['required', 'string', 'max:30', 'unique:people,phone_number,' . $this->route('personId') . ',id'],
            'email' => ['required', 'email', 'min:5', 'max:75', 'unique:people,email,' . $this->route('personId') . ',id'],
            'document_type_id' => ['required', 'exists:subitems,id,item_id,4'],
            'sex_id' => ['sometimes', 'exists:subitems,id,item_id,9'],
            'gender_id' => ['nullable', 'exists:subitems,id,item_id,10'],
            'blood_type_id' => ['nullable', 'exists:subitems,id,item_id,11'],
            'marital_status_id' => ['sometimes', 'exists:subitems,id,item_id,12'],
            'photo' => ['required', 'array'],
            'photo.file' => ['sometimes', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'photo.file_type_id' => ['required_with:photo.file', 'exists:subitems,id,item_id,16', 'in:100'],
            'photo.file_subtype_id' => ['required_with:photo.file', 'exists:subitems,id,item_id,17', 'in:125'],
            'file' => ['required', 'array'],
            'file.file' => ['sometimes', 'file', 'mimes:pdf', 'max:2048'],
            'file.file_type_id' => ['required_with:file.file', 'exists:subitems,id,item_id,16', 'in:100'],
            'file.file_subtype_id' => ['required_with:file.file', 'exists:subitems,id,item_id,17', 'in:102'],
            'signature' => ['sometimes', 'array'],
            'signature.file' => ['sometimes', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
            'signature.file_type_id' => ['required_with:file.file', 'exists:subitems,id,item_id,16', 'in:100'],
            'signature.file_subtype_id' => ['required_with:file.file', 'exists:subitems,id,item_id,17', 'in:130']
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'required_with' => 'Es requerido cuando :values está presente.',
            'regex' => 'Solo puede contener letras.',
            'string' => 'Debe ser una cadena de caracteres.',
            'email' => 'Debe ser una dirección de correo electrónico válida.',
            'unique' => 'Ya ha sido tomado.',
            'min' => 'Debe tener minimo :min caracteres.',
            'max' => 'No debe exceder los :max caracteres.',
            'photo.file.max' => 'No debe exceder los :max KB.',
            'file.file.max' => 'No debe exceder los :max KB.',
            'signature.file.max' => 'No debe exceder los :max KB.',
            'date' => 'Debe ser una fecha válida.',
            'date_format' => 'Formato inválido. Permitido :format.',
            'after' => 'Debe ser una fecha posterior a :date.',
            'exists' => 'No existe en la base de datos.',
            'confirmed' => 'No coincide con la confirmación.',
            'array' => 'Debe ser una lista de datos.',
            'mimes' => 'Extensión inválida. Permitidas :values.',
            'image' => 'Debe ser una imagen.',
            'file' => 'Debe ser un archivo.',
        ];
    }

    public function attributes()
    {
        return [
            'first_name' => 'Primer nombre',
            'second_name' => 'Segundo nombre',
            'first_last_name' => 'Primer apellido',
            'second_last_name' => 'Segundo apellido',
            'birth_date' => 'Fecha de nacimiento',
            'birth_city_id' => 'Ciudad de nacimiento',
            'document_number' => 'Número de documento',
            'document_number_confirmation' => 'Confirmacion del número de documento',
            'document_issue_city_id' => 'Ciudad de expedicion de documento',
            'document_issue_date' => 'Fecha de expedición del documento',
            'city_id' => 'Ciudad de residencia',
            'address' => 'Dirección',
            'neighborhood' => 'Barrio',
            'dialing_country_id' => 'Codigo de pais',
            'phone_number' => 'Número de teléfono',
            'phone_number_verified_at' => 'Fecha de verificación del teléfono',
            'email' => 'Correo electrónico',
            'email_verified_at' => 'Fecha de verificación del correo electrónico',
            'document_type_id' => 'Tipo de documento',
            'sex_id' => 'Sexo',
            'gender_id' => 'Genero',
            'blood_type_id' => 'Tipo de sangre',
            'marital_status_id' => 'Estado civil',
            'file' => 'Configuración documento de identidad',
            'file.file' => 'Documento de identidad',
            'file.file_type_id' => 'Tipo de archivo',
            'file.file_subtype_id' => 'Subtipo de archivo',
            'photo' => 'Configuración foto',
            'photo.file' => 'Foto',
            'photo.file_type_id' => 'Tipo de archivo',
            'photo.file_subtype_id' => 'Subtipo de archivo',
            'signature' => 'Configuración firma',
            'signature.file' => 'Firma',
            'signature.file_type_id' => 'Tipo de archivo',
            'signature.file_subtype_id' => 'Subtipo de archivo',
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

            $validation = collect($documentType->settings?->validations ?? [])->whereNull('deleted_at')
                ->where('country_id', $this->input('document_issue_country_id') ?? City::with('departament.country')->find($this->input('document_issue_city_id'))?->departament->country_id)->first();

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

            $country = Country::find($this->input('dialing_country_id'));

            if (!$country) {
                $validator->errors()->add('phone_number', 'No se ha podido validar el número de teléfono.');
                return;
            }

            $validations = collect($country->settings?->validations ?? [])->whereNull('deleted_at');

            if ($validations->isEmpty()) {
                $validator->errors()->add('phone_number', 'No hay formatos de validación de teléfono configurados para el país seleccionado.');
                return;
            }

            $passed = false;
            $messages = [];

            foreach ($validations as $rule) {
                $regex = stripslashes($rule->regex ?? '');
                $message = $rule->message ?? 'El número de teléfono no es válido.';

                if ($regex === '' || @preg_match($regex, '') === false) {
                    $messages[] = 'Existe una regla de validación mal configurada.';
                    continue;
                }

                if (preg_match($regex, $this->input('phone_number'))) {
                    $passed = true;
                    break;
                }

                $messages[] = $message;
            }

            if (!$passed) {
                foreach (array_unique($messages) as $msg) {
                    $validator->errors()->add('phone_number', $msg);
                }
            }
        });
    }
}
