<?php

namespace App\Http\Requests\Enterprise\Entity\Gallery;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class GalleryStoreRequest extends FormRequest
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
            'file_type_id' => ['required', 'exists:subitems,id,item_id,16', 'in:101'],
            'file_subtype_id' => ['required', 'exists:subitems,id,item_id,17', 'in:126,127,128'],
            'file' => ['required', 'file', 'mimes:png,jpg,jpeg', 'max:5120']
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'file' => 'Debe ser un archivo.',
            'exists' => 'No existe en la base de datos.',
            'max' => 'No debe exceder los :max KB.',
            'unique' => 'Ya ha sido tomado.',
            'mimes' => 'Extensión inválida. Permitidas :values.',
            'in' => 'Valor inválido. Permitidos: :values.'
        ];
    }

    public function attributes()
    {
        return [
            'entity_id' => 'Entidad',
            'file_type_id' => 'Tipo de archivo',
            'file_subtype_id' => 'Subtipo de archivo',
            'file' => 'Archivo'
        ];
    }
}

