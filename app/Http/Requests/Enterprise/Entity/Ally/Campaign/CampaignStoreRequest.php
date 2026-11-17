<?php

namespace App\Http\Requests\Enterprise\Entity\Ally\Campaign;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class CampaignStoreRequest extends FormRequest
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
            'ally_id' => ['required', 'exists:allies,id'],
            'name' => ['required', 'string', 'max:80', Rule::unique('campaigns', 'name')
                ->where(fn($query) => $query->where('ally_id', $this->input('ally_id')))],
            'description' => ['required', 'string', 'max:500'],
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
            'ally_id' => 'Aliado',
            'name' => 'Nombre',
            'description' => 'Descripción',
        ];
    }
}
