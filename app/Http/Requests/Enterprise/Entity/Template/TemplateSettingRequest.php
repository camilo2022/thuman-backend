<?php

namespace App\Http\Requests\Enterprise\Entity\Template;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TemplateSettingRequest extends FormRequest
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
            'settings' => ['required', 'array'],
            'settings.margins' => ['required', 'array'],
            'settings.margins.top' => ['required', 'array'],
            'settings.margins.top.length' => ['required', 'numeric', 'min:0'],
            'settings.margins.top.type' => ['required', 'string', 'in:px,cm'],
            'settings.margins.bottom' => ['required', 'array'],
            'settings.margins.bottom.length' => ['required', 'numeric', 'min:0'],
            'settings.margins.bottom.type' => ['required', 'string', 'in:px,cm'],
            'settings.margins.right' => ['required', 'array'],
            'settings.margins.right.length' => ['required', 'numeric', 'min:0'],
            'settings.margins.right.type' => ['required', 'string', 'in:px,cm'],
            'settings.margins.left' => ['required', 'array'],
            'settings.margins.left.length' => ['required', 'numeric', 'min:0'],
            'settings.margins.left.type' => ['required', 'string', 'in:px,cm'],
            'settings.orientation' => ['required', 'string', 'in:portrait,landscape'],
            'settings.size' => ['required', 'string', 'in:letter,legal'],
            'settings.sections' => ['required', 'array', 'min:1'],
            'settings.sections.*.title' => ['required', 'string', 'max:255'],
            'settings.sections.*.settings' => ['required', 'array'],
            'settings.sections.*.settings.is_visible' => ['required', 'boolean'],
            'settings.sections.*.settings.is_editable' => ['required', 'boolean'],
            'settings.sections.*.subsections' => ['required', 'array', 'min:1'],
            'settings.sections.*.subsections.*.title' => ['required', 'string', 'max:255'],
            'settings.sections.*.subsections.*.settings' => ['required', 'array'],
            'settings.sections.*.subsections.*.settings.is_visible' => ['required', 'boolean'],
            'settings.sections.*.subsections.*.settings.is_editable' => ['required', 'boolean'],
            'settings.sections.*.subsections.*.entry_type' => ['required', 'string', 'in:text,list'],
            'settings.sections.*.subsections.*.content' => ['required_if:sections.*.subsections.*.entry_type,text', 'nullable', 'string'],
            'settings.sections.*.subsections.*.list_type' => ['required_if:sections.*.subsections.*.entry_type,list', 'string', 'in:numeric,roman,alphabetic,ordinal'],
            'settings.sections.*.subsections.*.items' => ['required_if:sections.*.subsections.*.entry_type,list', 'array'],
            'settings.sections.*.subsections.*.items.*.title' => ['required', 'string', 'max:255'],
            'settings.sections.*.subsections.*.items.*.content' => ['required', 'string'],
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'required_if' => 'Es requerido.',
            'string' => 'Debe ser una cadena de caracteres.',
            'numeric' => 'Debe ser un valor numérico.',
            'array' => 'Debe ser una lista de datos.',
            'min' => 'Debe tener al menos :min elementos.',
            'max' => 'No debe exceder los :max caracteres.',
            'in' => 'Valor inválido. Permitidos: :values.',
            'boolean' => 'Debe ser verdadero o falso.',
        ];
    }

    public function attributes()
    {
        $attributes = [];
        foreach ($this->input('settings.sections', [[]]) as $i => $section) {
            $sectN = $i + 1;
            $sectBase = "settings.sections.$i";
            $attributes["$sectBase.title"] = "Título de la sección n° $sectN";
            $attributes["$sectBase.settings.is_visible"] = "Visibilidad de la sección n° $sectN";
            $attributes["$sectBase.settings.is_editable"] = "Editabilidad de la sección n° $sectN";
            foreach ($section['subsections'] ?? [[]] as $j => $subsection) {
                $subN = $j + 1;
                $subBase = "$sectBase.subsections.$j";
                $attributes["$subBase.title"] = "Título de la subsección n° $subN (sección n° $sectN)";
                $attributes["$subBase.entry_type"] = "Tipo de entrada de la subsección n° $subN (sección n° $sectN)";
                $attributes["$subBase.content"] = "Contenido de la subsección n° $subN (sección n° $sectN)";
                $attributes["$subBase.list_type"] = "Tipo de lista de la subsección n° $subN (sección n° $sectN)";
                $attributes["$subBase.settings.is_visible"] = "Visibilidad de la subsección n° $subN (sección n° $sectN)";
                $attributes["$subBase.settings.is_editable"] = "Editabilidad de la subsección n° $subN (sección n° $sectN)";
                foreach ($subsection['items'] ?? [[]] as $k => $item) {
                    $itemN = $k + 1;
                    $attributes["$subBase.items.$k.title"] = "Título del ítem n° $itemN (subsección n° $subN)";
                    $attributes["$subBase.items.$k.content"]= "Contenido del ítem n° $itemN (subsección n° $subN)";
                }
            }
        }

        return [
            'settings' => 'Configuración',
            'settings.margins' => 'Márgenes',
            'settings.margins.top' => 'Margen superior',
            'settings.margins.top.length' => 'Longitud del margen superior',
            'settings.margins.top.type' => 'Tipo del margen superior',
            'settings.margins.bottom' => 'Margen inferior',
            'settings.margins.bottom.length' => 'Longitud del margen inferior',
            'settings.margins.bottom.type' => 'Tipo del margen inferior',
            'settings.margins.right' => 'Margen derecho',
            'settings.margins.right.length' => 'Longitud del margen derecho',
            'settings.margins.right.type' => 'Tipo del margen derecho',
            'settings.margins.left' => 'Margen izquierdo',
            'settings.margins.left.length' => 'Longitud del margen izquierdo',
            'settings.margins.left.type' => 'Tipo del margen izquierdo',
            'settings.orientation' => 'Orientación del documento',
            'settings.size' => 'Tamaño del documento',
            'settings.sections' => 'Secciones',
            'settings.sections.*.title' => 'Título de la sección',
            'settings.sections.*.settings' => 'Configuración de la sección',
            'settings.sections.*.settings.is_visible' => 'Visibilidad de la sección',
            'settings.sections.*.settings.is_editable' => 'Editabilidad de la sección',
            'settings.sections.*.subsections' => 'Subsecciones',
            'settings.sections.*.subsections.*.title' => 'Título de la subsección',
            'settings.sections.*.subsections.*.settings' => 'Configuración de la subsección',
            'settings.sections.*.subsections.*.settings.is_visible' => 'Visibilidad de la subsección',
            'settings.sections.*.subsections.*.settings.is_editable' => 'Editabilidad de la subsección',
            'settings.sections.*.subsections.*.entry_type' => 'Tipo de entrada',
            'settings.sections.*.subsections.*.content' => 'Contenido de la subsección',
            'settings.sections.*.subsections.*.list_type' => 'Tipo de lista',
            'settings.sections.*.subsections.*.items' => 'Ítems de la lista',
            'settings.sections.*.subsections.*.items.*.title' => 'Título del ítem',
            'settings.sections.*.subsections.*.items.*.content' => 'Contenido del ítem',
            ...$attributes
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {

            $margins = $this->input('settings.margins', []);

            $limits = [
                'cm' => 5,
                'px' => 300,
            ];

            foreach (['top', 'bottom', 'left', 'right'] as $side) {

                $type = $margins[$side]['type'] ?? null;
                $length = $margins[$side]['length'] ?? null;

                if (!$type || $length === null) {
                    continue;
                }

                if (!isset($limits[$type])) {
                    continue;
                }

                $max = $limits[$type];

                if ($length > $max) {
                    $validator->errors()->add(
                        "settings.margins.$side.length",
                        "Valor no permitido para el margen '$side'. Límite: {$max}{$type}."
                    );
                }
            }
        });
    }

}
