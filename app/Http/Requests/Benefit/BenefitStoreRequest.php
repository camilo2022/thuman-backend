<?php

namespace App\Http\Requests\Benefit;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class BenefitStoreRequest extends FormRequest
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
            'item_id' => ['required', 'exists:items,id', 'in:19'],
            'name' => ['required', 'string', 'max:80', 'unique:subitems,name,NULL,id,item_id,19'],
            'description' => ['required', 'string', 'max:500'],
            'icon' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048', 'dimensions:min_width=50,min_height=50,max_width=1000,max_height=1000'],
            'file_type_id' => ['required', 'exists:subitems,id,item_id,16', 'in:97'],
            'file_subtype_id' => ['required', 'exists:subitems,id,item_id,17', 'in:108']
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'string' => 'Debe ser una cadena de caracteres.',
            'unique' => 'Ya ha sido tomado.',
            'max' => 'No debe exceder los :max caracteres.',
            'image' => 'Debe ser una imagen.',
            'mimes' => 'Extensión inválida. Permitidas :values.',
            'logo.max' => 'No debe exceder los :max KB.',
            'dimensions' => 'Las dimensiones de la imagen no son válidas. Permitidas 50x50 px mínimo, 1000x1000 px máximo.',
            'exists' => 'No existe en la base de datos.',
            'array' => 'Debe ser una lista de datos.',
        ];
    }

    public function attributes()
    {
        return [
            'item_id' => 'Categorización',
            'name' => 'Nombre',
            'description' => 'Descripción',
            'icon' => 'Icono',
            'file_type_id' => 'Tipo de archivo',
            'file_subtype_id' => 'Subtipo de archivo'
        ];
    }
}
