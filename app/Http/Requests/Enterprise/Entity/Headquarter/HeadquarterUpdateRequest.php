<?php

namespace App\Http\Requests\Enterprise\Entity\Headquarter;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class HeadquarterUpdateRequest extends FormRequest
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
            'entity_id' => ['required', 'exists:entities,id'],
            'name' => ['required', 'string', 'max:80', 'unique:headquarters,name,' . $this->route('headquarterId') .',id'],
            'city_id' => ['required', 'exists:cities,id'],
            'address' => ['required', 'string', 'min:5', 'max:110']
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'exists' => 'No existe en la base de datos.',
            'string' => 'Debe ser una cadena de caracteres.',
            'unique' => 'Ya ha sido tomado.',
            'max' => 'No debe exceder los :max caracteres.',
            'min' => 'Debe ser mínimo :min caracteres.',
        ];
    }

    public function attributes()
    {
        return [
            'entity_id' => 'Entidad internacional',
            'name' => 'Nombre',
            'city_id' => 'Ciudad',
            'address' => 'Dirección'
        ];
    }
}
