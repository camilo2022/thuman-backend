<?php

namespace App\Http\Requests\Profile\EmergencyContact;

use App\Models\Country;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class EmergencyContactStoreRequest extends FormRequest
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
            'profile_id' => ['required', 'exists:profiles,id', 'unique:emergency_contacts,profile_id'],
            'first_name' => ['required', 'string', 'min:1', 'max:25'],
            'second_name' => ['nullable', 'string', 'min:1', 'max:25'],
            'first_last_name' => ['required', 'string', 'min:3', 'max:25'],
            'second_last_name' => ['nullable', 'string', 'min:3', 'max:25'],
            'relationship_id' => ['required', 'exists:subitems,id,item_id,52'],
            'dialing_country_id' => ['required', 'exists:countries,id'],
            'phone_number' => ['required', 'string', 'max:30'],
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'exists' => 'No existe en la base de datos.',
            'numeric' => 'Debe ser un valor numérico.',
            'unique' => 'Ya ha sido tomado.',
            'min' => 'Debe tener minimo :min caracteres.',
            'max' => 'No debe exceder los :max caracteres.',
        ];
    }

    public function attributes()
    {
        return [
            'profile_id' => 'Perfil',
            'first_name' => 'Primer nombre',
            'second_name' => 'Segundo nombre',
            'first_last_name' => 'Primer apellido',
            'second_last_name' => 'Segundo apellido',
            'neighborhood' => 'Barrio',
            'relationship_id' => 'Parentesco'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $country = Country::find($this->input('dialing_country_id'));

            if (!$country) {
                $validator->errors()->add('phone_number', 'No se ha podido validar.');
            } else {
                $validations = collect($country->settings?->validations ?? [])->whereNull('deleted_at');

                if ($validations->isEmpty()) {
                    $validator->errors()->add('phone_number', 'No hay formatos de validación de teléfono configurados para el país seleccionado.');
                } else {
                    $phone_number = $this->input('phone_number');
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
                            $validator->errors()->add('phone_number', $msg);
                        }
                    }
                }
            }
        });
    }
}
