<?php

namespace App\Http\Requests\Position\RequiredSkill;

use App\Models\Skill;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RequiredSkillStoreRequest extends FormRequest
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
            'position_id' => ['required', 'exists:positions,id'],
            'skill_id' => ['required', 'exists:subitems,id,item_id,29', 'unique:required_skills,skill_id,NULL,id,position_id,' . $this->input('position_id')],
            'percentage' => ['required', 'numeric'],
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'exists' => 'No existe en la base de datos.',
            'numeric' => 'Debe ser un valor numérico.',
            'unique' => 'Ya ha sido tomado.'
        ];
    }

    public function attributes()
    {
        return [
            'profile_id' => 'Perfil',
            'skill_id' => 'Habilidad',
            'percentage' => 'Porcentaje'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $subitem = Skill::with('skill_type')->find($this->input('skill_id'));

            if (is_object($subitem?->skill_type->first()?->settings) && isset($subitem?->skill_type->first()?->settings->regex)) {
                $regex = stripslashes($subitem->skill_type->first()?->settings->regex);
                $message = $subitem->skill_type->first()?->settings->message ?? 'El formato no es válido.';

                if (!preg_match($regex, $this->integer('percentage'))) {
                    $validator->errors()->add('percentage', $message);
                }
            } else {
                $validator->errors()->add('percentage', 'No se ha podido validar.');
            }
        });
    }
}
