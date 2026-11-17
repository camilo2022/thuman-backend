<?php

namespace App\Http\Requests\Enterprise\Entity;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class EntityProfileRequest extends FormRequest
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
            'banner' => ['required', 'array'],
            'banner.image' => ['sometimes', 'image', 'mimes:jpg,jpeg,png', 'max:5120', 'dimensions:min_width=1400,min_height=350,max_width=2560,max_height=600'],
            'banner.file_type_id' => ['required_with:banner.image', 'exists:subitems,id,item_id,16', 'in:97'],
            'banner.file_subtype_id' => ['required_with:banner.image', 'exists:subitems,id,item_id,17', 'in:107'],
            'mission' => ['required', 'array'],
            'mission.text' => ['required', 'string', 'min:7', 'max:2000'],
            'mission.image' => ['sometimes', 'image', 'mimes:jpg,jpeg,png', 'max:5120', 'dimensions:min_width=600,min_height=600,max_width=1800,max_height=1200'],
            'mission.file_type_id' => ['required_with:mission.image', 'exists:subitems,id,item_id,16', 'in:97'],
            'mission.file_subtype_id' => ['required_with:mission.image', 'exists:subitems,id,item_id,17', 'in:108'],
            'vision' => ['required', 'array'],
            'vision.text' => ['required', 'string', 'min:7', 'max:2000'],
            'vision.image' => ['sometimes', 'image', 'mimes:jpg,jpeg,png', 'max:5120', 'dimensions:min_width=600,min_height=600,max_width=1800,max_height=1200'],
            'vision.file_type_id' => ['required_with:vision.image', 'exists:subitems,id,item_id,16', 'in:97'],
            'vision.file_subtype_id' => ['required_with:vision.image', 'exists:subitems,id,item_id,17', 'in:109'],
            'about_us' => ['required', 'array'],
            'about_us.text' => ['required', 'string', 'min:7', 'max:2000'],
            'about_us.image' => ['sometimes', 'image', 'mimes:jpg,jpeg,png', 'max:5120', 'dimensions:min_width=600,min_height=600,max_width=1800,max_height=1200'],
            'about_us.file_type_id' => ['required_with:about_us.image', 'exists:subitems,id,item_id,16', 'in:97'],
            'about_us.file_subtype_id' => ['required_with:about_us.image', 'exists:subitems,id,item_id,17', 'in:110'],
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'required_with' => 'Es requerido cuando :values está presente.',
            'string' => 'Debe ser una cadena de caracteres.',
            'unique' => 'Ya ha sido tomado.',
            'mission.image.max' => 'No debe exceder los :max KB.',
            'vision.image.max' => 'No debe exceder los :max KB.',
            'about_us.image.max' => 'No debe exceder los :max KB.',
            'max' => 'No debe exceder los :max caracteres.',
            'min' => 'Debe ser mínimo :min caracteres.',
            'image' => 'Debe ser una imagen.',
            'mimes' => 'Extensión inválida. Permitidas :values.',
            'banner.image.dimensions' => 'Debe tener mínimo 1400x350 px y máximo 2560x600 px, en formato horizontal.',
            'mission.image.dimensions' => 'Debe tener mínimo 900x600 px y máximo 1800x1200 px.',
            'vision.image.dimensions' => 'Debe tener mínimo 600x600 px y máximo 1200x1200 px.',
            'about_us.image.dimensions' => 'Debe tener mínimo 600x600 px y máximo 1200x1200 px.',
            'exists' => 'No existe en la base de datos.',
            'array' => 'Debe ser una lista de datos.',
        ];
    }

    public function attributes()
    {
        return [
            'banner' => 'Configuración banner',
            'banner.image' => 'Imagen Banner',
            'banner.file_type_id' => 'Tipo de archivo',
            'banner.file_subtype_id' => 'Subtipo de archivo',
            'mission' => 'Configuración misión',
            'mission.text' => 'Misión',
            'mission.image' => 'Imagen Misión',
            'mission.file_type_id' => 'Tipo de archivo',
            'mission.file_subtype_id' => 'Subtipo de archivo',
            'vision' => 'Configuración visión',
            'vision.text' => 'Visión',
            'vision.image' => 'Imagen Visión',
            'vision.file_type_id' => 'Tipo de archivo',
            'vision.file_subtype_id' => 'Subtipo de archivo',
            'about_us' => 'Configuración sobre nosotros',
            'about_us.text' => 'Sobre nosotros',
            'about_us.image' => 'Imagen Sobre nosotros',
            'about_us.file_type_id' => 'Tipo de archivo',
            'about_us.file_subtype_id' => 'Subtipo de archivo',
        ];
    }
}
