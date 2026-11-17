<?php

namespace App\Http\Requests\Enterprise\Entity\Template;

use App\Models\Entity;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TemplateStoreRequest extends FormRequest
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
            'file_type_id' => ['required', 'exists:subitems,id,item_id,16', 'in:98'],
            'file_subtype_id' => ['required', 'exists:subitems,id,item_id,17', 'unique:files,file_subtype_id,NULL,id,file_type_id,98,model_type,' . Entity::class . ',model_id,' . $this->input('entity_id')],
            'file' => ['required', 'mimetypes:image/jpeg,image/png', 'mimes:jpg,jpeg,png', 'max:5120', 'dimensions:min_width=800,min_height=1000,max_width=2000,max_height=2600']
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
            'mimetypes' => 'Extensión inválida. Permitidas :values.',
            'file.dimensions' => 'Debe tener dimensiones aproximadas tipo carta u oficio (mínimo 800x1000 px y máximo 2000x2600 px).'
        ];
    }

    public function attributes()
    {
        return [
            'entity_id' => 'Entidad',
            'file_type_id' => 'Tipo de archivo',
            'file_subtype_id' => 'Subtipo de archivo',
            'file' => 'Plantilla'
        ];
    }
}

