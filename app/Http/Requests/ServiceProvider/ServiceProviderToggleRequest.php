<?php

namespace App\Http\Requests\ServiceProvider;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ServiceProviderToggleRequest extends FormRequest
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
            'service_type_id' => ['required_without:country_id', 'nullable', 'exists:subitems,id,item_id,34'],
            'country_id' => ['required_without:service_type_id', 'nullable', 'exists:countries,id'],
        ];
    }

    public function messages()
    {
        return [
            'required_without' => 'Es requerido cuando :values no está presente.',
            'exists' => 'No existe en la base de datos.'
        ];
    }

    public function attributes()
    {
        return [
            'service_type_id' => 'Tipo de servicio',
            'country_id' => 'País',
        ];
    }
}
