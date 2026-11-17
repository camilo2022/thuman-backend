<?php

namespace App\Http\Requests\Contract;

use App\Models\Application;
use App\Models\BankCertificate;
use App\Models\CompensationFund;
use App\Models\ContractType;
use App\Models\FiscalDocumentCertificate;
use App\Models\HealthEntityAffiliation;
use App\Models\PensionFundAffiliation;
use App\Models\RiskManagerAffiliation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ContractStoreRequest extends FormRequest
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
        $contractType = ContractType::find($this->input('contract_type_id'));

        $requiresEndDate = $contractType?->settings?->requires_end_date ?? false;

        return [
            'application_id' => ['required', 'exists:applications,id,person_id,' . $this->input('person_id'), 'unique:contracts,application_id'],
            'person_id' => ['required', 'exists:people,id', 'unique:contracts,person_id,NULL,id,deleted_at,NULL'],
            'headquarter_id' => ['required', 'exists:headquarters,id'],
            'contract_type_id' => ['required', 'exists:subitems,id,item_id,1'],
            'position_id' => ['required', 'exists:positions,id'],
            'salary' => ['required', 'numeric'],
            'transport_allowance' => ['required', 'numeric'],
            'payment_period_id' => ['required', 'exists:subitems,id,item_id,36'],
            'start_date' => ['required', 'date', 'date_format:Y-m-d', 'after_or_equal:today'],
            'end_date' => [$requiresEndDate ? 'required' : 'nullable', 'date', 'date_format:Y-m-d', 'after_or_equal:start_date'],
            'settings' => ['required', 'array'],
            'settings.documents' => ['required', 'array', 'min:1'],
            'settings.documents.*' => ['required', 'array'],
            'settings.documents.*.template' => ['required', 'array'],
            'settings.documents.*.variables' => ['required', 'array'],
            'settings.documents.*.template.settings.margins' => ['required', 'array'],
            'settings.documents.*.template.settings.margins.top' => ['required', 'array'],
            'settings.documents.*.template.settings.margins.top.length' => ['required', 'numeric', 'min:0'],
            'settings.documents.*.template.settings.margins.top.type' => ['required', 'string', 'in:px,cm'],
            'settings.documents.*.template.settings.margins.bottom' => ['required', 'array'],
            'settings.documents.*.template.settings.margins.bottom.length' => ['required', 'numeric', 'min:0'],
            'settings.documents.*.template.settings.margins.bottom.type' => ['required', 'string', 'in:px,cm'],
            'settings.documents.*.template.settings.margins.right' => ['required', 'array'],
            'settings.documents.*.template.settings.margins.right.length' => ['required', 'numeric', 'min:0'],
            'settings.documents.*.template.settings.margins.right.type' => ['required', 'string', 'in:px,cm'],
            'settings.documents.*.template.settings.margins.left' => ['required', 'array'],
            'settings.documents.*.template.settings.margins.left.length' => ['required', 'numeric', 'min:0'],
            'settings.documents.*.template.settings.margins.left.type' => ['required', 'string', 'in:px,cm'],
            'settings.documents.*.template.settings.orientation' => ['required', 'string', 'in:portrait,landscape'],
            'settings.documents.*.template.settings.size' => ['required', 'string', 'in:letter,legal'],
            'settings.documents.*.template.settings.sections' => ['required', 'array', 'min:1'],
            'settings.documents.*.template.settings.sections.*.title' => ['required', 'string', 'max:255'],
            'settings.documents.*.template.settings.sections.*.settings' => ['required', 'array'],
            'settings.documents.*.template.settings.sections.*.settings.is_visible' => ['required', 'boolean'],
            'settings.documents.*.template.settings.sections.*.settings.is_editable' => ['required', 'boolean'],
            'settings.documents.*.template.settings.sections.*.subsections' => ['required', 'array', 'min:1'],
            'settings.documents.*.template.settings.sections.*.subsections.*.title' => ['required', 'string', 'max:255'],
            'settings.documents.*.template.settings.sections.*.subsections.*.settings' => ['required', 'array'],
            'settings.documents.*.template.settings.sections.*.subsections.*.settings.is_visible' => ['required', 'boolean'],
            'settings.documents.*.template.settings.sections.*.subsections.*.settings.is_editable' => ['required', 'boolean'],
            'settings.documents.*.template.settings.sections.*.subsections.*.entry_type' => ['required', 'string', 'in:text,list'],
            'settings.documents.*.template.settings.sections.*.subsections.*.content' => ['required_if:sections.*.subsections.*.entry_type,text', 'nullable', 'string'],
            'settings.documents.*.template.settings.sections.*.subsections.*.list_type' => ['required_if:sections.*.subsections.*.entry_type,list', 'string', 'in:numeric,roman,alphabetic,ordinal'],
            'settings.documents.*.template.settings.sections.*.subsections.*.items' => ['required_if:sections.*.subsections.*.entry_type,list', 'array'],
            'settings.documents.*.template.settings.sections.*.subsections.*.items.*.title' => ['required', 'string', 'max:255'],
            'settings.documents.*.template.settings.sections.*.subsections.*.items.*.content' => ['required', 'string'],
            'file_subtypes_id' => ['required', 'array', 'min:1'],
            'file_subtypes_id.*' => ['required', 'exists:subitems,id,item_id,17']
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'required_if' => 'Es requerido.',
            'string' => 'Debe ser una cadena de caracteres.',
            'numeric' => 'Debe ser un valor numérico.',
            'unique' => 'Ya ha sido tomado.',
            'array' => 'Debe ser una lista de datos.',
            'min' => 'Debe tener al menos :min elementos.',
            'max' => 'No debe exceder los :max caracteres.',
            'in' => 'Valor inválido. Permitidos: :values.',
            'boolean' => 'Debe ser verdadero o falso.',
            'after_or_equal' => 'Debe ser una fecha posterior o igual a hoy.',
            'date' => 'Debe ser una fecha válida.',
            'date_format' => 'Formato inválido. Permitido :format.',
            'exists' => 'No existe en la base de datos.'
        ];
    }

    public function attributes()
    {
        return [
            'application_id' => 'Aplicación',
            'person_id' => 'Persona',
            'headquarter_id' => 'Sede',
            'template_id' => 'Plantilla',
            'contract_type_id' => 'Tipo de contrato',
            'position_id' => 'Cargo',
            'salary' => 'Salario',
            'transport_allowance' => 'Auxilio de transporte',
            'payment_period_id' => 'Período de pago',
            'start_date' => 'Fecha de inicio',
            'end_date' => 'Fecha de finalización',
            'settings' => 'Configuración',
            'settings.documents' => 'Documentos',
            'settings.documents.*' => 'Documento',
            'settings.documents.*.template' => 'Plantilla del documento',
            'settings.documents.*.variables' => 'Variables del documento',
            'settings.documents.*.template.settings.margins' => 'Márgenes',
            'settings.documents.*.template.settings.margins.top' => 'Margen superior',
            'settings.documents.*.template.settings.margins.top.length' => 'Longitud del margen superior',
            'settings.documents.*.template.settings.margins.top.type' => 'Tipo del margen superior',
            'settings.documents.*.template.settings.margins.bottom' => 'Margen inferior',
            'settings.documents.*.template.settings.margins.bottom.length' => 'Longitud del margen inferior',
            'settings.documents.*.template.settings.margins.bottom.type' => 'Tipo del margen inferior',
            'settings.documents.*.template.settings.margins.right' => 'Margen derecho',
            'settings.documents.*.template.settings.margins.right.length' => 'Longitud del margen derecho',
            'settings.documents.*.template.settings.margins.right.type' => 'Tipo del margen derecho',
            'settings.documents.*.template.settings.margins.left' => 'Margen izquierdo',
            'settings.documents.*.template.settings.margins.left.length' => 'Longitud del margen izquierdo',
            'settings.documents.*.template.settings.margins.left.type' => 'Tipo del margen izquierdo',
            'settings.documents.*.template.settings.orientation' => 'Orientación del documento',
            'settings.documents.*.template.settings.size' => 'Tamaño del documento',
            'settings.documents.*.template.settings.sections' => 'Secciones',
            'settings.documents.*.template.settings.sections.*.title' => 'Título de la sección',
            'settings.documents.*.template.settings.sections.*.settings' => 'Configuración de la sección',
            'settings.documents.*.template.settings.sections.*.settings.is_visible' => 'Visibilidad de la sección',
            'settings.documents.*.template.settings.sections.*.settings.is_editable' => 'Editabilidad de la sección',
            'settings.documents.*.template.settings.sections.*.subsections' => 'Subsecciones',
            'settings.documents.*.template.settings.sections.*.subsections.*.title' => 'Título de la subsección',
            'settings.documents.*.template.settings.sections.*.subsections.*.settings' => 'Configuración de la subsección',
            'settings.documents.*.template.settings.sections.*.subsections.*.settings.is_visible' => 'Visibilidad de la subsección',
            'settings.documents.*.template.settings.sections.*.subsections.*.settings.is_editable' => 'Editabilidad de la subsección',
            'settings.documents.*.template.settings.sections.*.subsections.*.entry_type' => 'Tipo de entrada',
            'settings.documents.*.template.settings.sections.*.subsections.*.content' => 'Contenido de la subsección',
            'settings.documents.*.template.settings.sections.*.subsections.*.list_type' => 'Tipo de lista',
            'settings.documents.*.template.settings.sections.*.subsections.*.items' => 'Ítems de la lista',
            'settings.documents.*.template.settings.sections.*.subsections.*.items.*.title' => 'Título del ítem',
            'settings.documents.*.template.settings.sections.*.subsections.*.items.*.content' => 'Contenido del ítem',
            'file_subtypes_id' => 'Plantillas contractuales',
            'file_subtypes_id.*' => 'Plantilla contractual'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $application = Application::with(['vacant' => ['contract_type'], 'person' => [
                'health_entity_affiliation',
                'pension_fund_affiliation',
                'risk_manager_affiliation',
                'bank_certificate',
                'compensation_fund_affiliation',
                'fiscal_document_certificate',
            ]])->find($this->integer('application_id'));
            if (!$application) {
                $validator->errors()->add('application_id', 'Aplicación no encontrada.');
                return;
            }

            if (!in_array($application->status, [Application::HIRING], true)) {
                $validator->errors()->add('application_id', 'Los estados permitidos para realizar esta acción son: ' . implode(', ', [Application::HIRING]) . '.');
            }

            if ($application->vacant?->contract_type?->settings?->health_entity_affiliation && $application->person?->health_entity_affiliation?->status != HealthEntityAffiliation::APPROVED) {
                $validator->errors()->add('health_entity_affiliation', 'La afiliación a entidad de salud hace parte de la documentación requerida.');
            }

            if ($application->vacant?->contract_type?->settings?->pension_fund_affiliation && $application->person?->pension_fund_affiliation?->status != PensionFundAffiliation::APPROVED) {
                $validator->errors()->add('health_entity_affiliation', 'La afiliación al fondo de pensiones hace parte de la documentación requerida.');
            }

            if ($application->vacant?->contract_type?->settings?->risk_manager_affiliation && $application->person?->risk_manager_affiliation?->status != RiskManagerAffiliation::APPROVED) {
                $validator->errors()->add('health_entity_affiliation', 'La afiliación a la administradora de riesgos laborales hace parte de la documentación requerida.');
            }

            if ($application->vacant?->contract_type?->settings?->bank_certificate && $application->person?->bank_certificate?->status != BankCertificate::APPROVED) {
                $validator->errors()->add('health_entity_affiliation', 'El certificado bancario hace parte de la documentación requerida.');
            }

            if ($application->vacant?->contract_type?->settings?->compensation_fund_affiliation && $application->person?->compensation_fund_affiliation?->status != CompensationFund::APPROVED) {
                $validator->errors()->add('health_entity_affiliation', 'La afiliación al fondo de compensación hace parte de la documentación requerida.');
            }

            if ($application->vacant?->contract_type?->settings?->fiscal_document_certificate && $application->person?->fiscal_document_certificate?->status != FiscalDocumentCertificate::APPROVED) {
                $validator->errors()->add('health_entity_affiliation', 'El documento fiscal hace parte de la documentación requerida.');
            }
        });
    }
}
