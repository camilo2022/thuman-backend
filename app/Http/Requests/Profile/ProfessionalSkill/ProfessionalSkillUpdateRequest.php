<?php

namespace App\Http\Requests\Profile\ProfessionalSkill;

use App\Models\Skill;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class ProfessionalSkillUpdateRequest extends FormRequest
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
            'profile_id' => ['required', 'exists:profiles,id'],
            'skill_id' => ['required', 'exists:subitems,id,item_id,29', 'unique:professional_skills,skill_id,' . $this->route('professionalSkillId') . ',id,profile_id,' . $this->input('profile_id')],
            'percentage' => ['required', 'numeric'],
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'exists' => 'No existe en la base de datos.',
            'numeric' => 'Debe ser un valor numérico.'
        ];
    }

    public function attributes()
    {
        return [
            'profile_id' => 'Perfil',
            'skill_id' => 'Habilidad',
            'percentage' => 'Porcentaje',
            'unique' => 'Ya ha sido tomado.'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $subitem = Skill::with('skill_type')->find($this->input('skill_id'));

            if (is_object($subitem?->skill_type->first()?->settings) && isset($subitem?->skill_type->first()?->settings->regex)) {
                $regex = stripslashes($subitem->skill_type->first()?->settings->regex);
                $message = $subitem->skill_type->first()?->settings->message ?? 'El formato no es válido.';

                if (!preg_match($regex, $this->input('percentage'))) {
                    $validator->errors()->add('percentage', $message);
                }
            } else {
                $validator->errors()->add('percentage', 'No se ha podido validar.');
            }
        });
    }
}
