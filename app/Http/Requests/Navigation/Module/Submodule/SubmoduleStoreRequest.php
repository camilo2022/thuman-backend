<?php

namespace App\Http\Requests\Navigation\Module\Submodule;

use App\Models\Module;
use App\Models\Submodule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class SubmoduleStoreRequest extends FormRequest
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
            'model_type' => ['required', 'in:' . implode(',', [Module::class, Submodule::class])],
            'model_id' => ['required', $this->model_type === Submodule::class ? 'exists:submodules,id,type,!null' : 'exists:modules,id'],
            'type' => ['nullable', 'in:Desplegable,Vista'],
            'role_id' => [
                'nullable',
                'exists:roles,id',
                Rule::unique('submodules', 'role_id')
                    ->where(function ($query) {
                        if ($this->filled('permission_id')) {
                            $query->where('permission_id', $this->input('permission_id'));
                        }
                    }),
            ],
            'permission_id' => [
                'nullable', 'required_with:role_id',
                'exists:permissions,id',
                Rule::unique('submodules', 'permission_id')
                    ->where(function ($query) {
                        if ($this->filled('role_id')) {
                            $query->where('role_id', $this->input('role_id'));
                        }
                    }),
            ],
            'name' => ['required', 'string', 'max:80', 'unique:submodules,name'],
            'route' => ['nullable', 'string', 'max:255'],
            'icon' => ['required', 'string', 'max:2000'],
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            if ($this->model_type === Module::class) {

                if ($this->type !== 'Desplegable') {

                    if (!$this->role_id) {
                        $validator->errors()->add('role_id', 'Es requerido.');
                    }

                    if (!$this->permission_id) {
                        $validator->errors()->add('permission_id', 'Es requerido.');
                    }

                    if (!$this->route) {
                        $validator->errors()->add('route', 'Es requerido.');
                    }
                }

                if ($this->type === 'Desplegable') {

                    if ($this->role_id) {
                        $validator->errors()->add('role_id', 'No permitido.');
                    }

                    if ($this->permission_id) {
                        $validator->errors()->add('permission_id', 'No permitido.');
                    }

                    if ($this->route) {
                        $validator->errors()->add('route', 'No permitido.');
                    }
                }
            }

            if ($this->model_type === Submodule::class) {

                $parent = Submodule::find($this->model_id);

                // Tercer nivel → type obligatorio null
                if ($parent && $parent->model_type === Submodule::class && $this->type !== null) {
                    $validator->errors()->add('type', 'No permitido.');
                }

                if ($this->type === 'Vista') {
                    $validator->errors()->add('type', 'No permitido.');
                }

                if ($this->type === 'Desplegable') {

                    if ($this->role_id) {
                        $validator->errors()->add('role_id', 'No permitido.');
                    }

                    if ($this->permission_id) {
                        $validator->errors()->add('permission_id', 'No permitido.');
                    }

                    if ($this->route) {
                        $validator->errors()->add('route', 'No permitido.');
                    }
                }

                if (!$this->type) {

                    if (!$this->role_id) {
                        $validator->errors()->add('role_id', 'Es requerido.');
                    }

                    if (!$this->permission_id) {
                        $validator->errors()->add('permission_id', 'Es requerido.');
                    }

                    if (!$this->route) {
                        $validator->errors()->add('route', 'Es requerido.');
                    }
                }
            }
        });
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'string' => 'Debe ser una cadena de caracteres.',
            'unique' => 'Ya ha sido tomado.',
            'exists' => 'No existe en la base de datos.',
            'max' => 'No debe exceder los :max caracteres.',
            'in' => 'Valor inválido.'
        ];
    }

    public function attributes()
    {
        return [
            'model_type' => 'Tipo (Módulo / Submódulo)',
            'model_id' => 'Elemento padre',
            'role_id' => 'Rol',
            'permission_id' => 'Permiso',
            'name' => 'Nombre',
            'route' => 'Ruta',
            'icon' => 'Ícono',
            'type' => 'Tipo',
        ];
    }
}
