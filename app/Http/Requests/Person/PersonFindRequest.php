<?php

namespace App\Http\Requests\Person;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class PersonFindRequest extends FormRequest
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
            'document_type_id' => [$this->route('personId') ? 'nullable' : ($this->filled('document_number') ? 'required' : 'nullable'), 'exists:subitems,id,item_id,4'],
            'document_number' => [$this->route('personId') ? 'nullable' : 'required_without_all:phone_number,email,employee_id', 'string', 'exists:people,document_number'],
            'phone_number' => [$this->route('personId') ? 'nullable' : 'required_without_all:document_number,email,employee_id', 'string', 'exists:people,phone_number'],
            'email' => [$this->route('personId') ? 'nullable' : 'required_without_all:document_number,phone_number,employee_id', 'email', 'exists:people,email'],
            'employee_id' => [$this->route('personId') ? 'nullable' : 'required_without_all:document_number,phone_number,email', 'exists:employees,id']
        ];
    }

    public function messages()
    {
        return [
            'required_with' => 'Es requerido cuando :values está presente.',
            'required_without_all' => 'Es requerido cuando :values no está presente.',
            'string' => 'Debe ser una cadena de caracteres.',
            'email' => 'Debe ser una dirección de correo electrónico válida.',
            'exists' => 'No existe en la base de datos.'
        ];
    }

    public function attributes()
    {
        return [
            'document_type_id' => 'Tipo de documento',
            'document_number' => 'Número de documento',
            'phone_number' => 'Número de teléfono',
            'email' => 'Correo electrónico',
            'employee_id' => 'Empleado'
        ];
    }
}
