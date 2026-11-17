<?php

namespace App\Http\Requests\Enterprise\Entity\Template;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TemplateFindRequest extends FormRequest
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
            'entity_id' => [$this->route('templateId') ? 'nullable' : 'required', 'exists:entities,id'],
            'file_subtype_id' => [$this->route('templateId') ? 'nullable' : 'required', 'exists:subitems,id,item_id,17', 'in:112,113,114,115,116,117,118,119,120']
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'exists' => 'No existe en la base de datos.',
        ];
    }

    public function attributes()
    {
        return [
            'entity_id' => 'Entidad',
            'file_subtype_id' => 'Subtipo de archivo',
        ];
    }
}
