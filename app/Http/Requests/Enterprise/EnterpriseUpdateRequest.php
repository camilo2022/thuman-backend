<?php

namespace App\Http\Requests\Enterprise;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class EnterpriseUpdateRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:80', 'unique:enterprises,name,' . $this->route('enterpriseId') .',id'],
            'description' => ['nullable', 'string', 'max:255'],
            'logo' => ['sometimes', 'image', 'mimes:jpg,jpeg,png', 'max:2048', 'dimensions:min_width=100,min_height=100,max_width=2000,max_height=2000'],
            'file_type_id' => ['required_with:logo', 'exists:subitems,id,item_id,16', 'in:97'],
            'file_subtype_id' => ['required_with:logo', 'exists:subitems,id,item_id,17', 'in:108']
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'required_with' => 'Es requerido cuando :values está presente.',
            'string' => 'Debe ser una cadena de caracteres.',
            'unique' => 'Ya ha sido tomado.',
            'max' => 'No debe exceder los :max caracteres.',
            'image' => 'Debe ser una imagen.',
            'mimes' => 'Extensión inválida. Permitidas :values.',
            'logo.max' => 'No debe exceder los :max KB.',
            'logo.dimensions' => 'La imagen debe tener mínimo 100x100 px y máximo 2000x2000 px.',
            'exists' => 'No existe en la base de datos.',
            'array' => 'Debe ser una lista de datos.',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Nombre',
            'description' => 'Descrpción',
            'logo' => 'Logo',
            'file_type_id' => 'Tipo de archivo',
            'file_subtype_id' => 'Subtipo de archivo',
        ];
    }
}
