<?php

namespace App\Observers\Contract;

use App\Models\Contract;
use App\Models\Employee;
use App\Models\File;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ContractObserver
{
    /**
     * Handle the Contract "created" event.
     */
    public function created(Contract $contract): void
    {
        $this->handleContractStatus($contract);
    }

    /**
     * Handle the Contract "updated" event.
     */
    public function updated(Contract $contract): void
    {
        $this->handleContractStatus($contract);
    }

    /**
     * Handle the Contract "deleted" event.
     */
    public function deleted(Contract $contract): void
    {
        //
    }

    /**
     * Handle the Contract "restored" event.
     */
    public function restored(Contract $contract): void
    {
        //
    }

    /**
     * Handle the Contract "force deleted" event.
     */
    public function forceDeleted(Contract $contract): void
    {
        //
    }

    protected function handleContractStatus(Contract $contract): void
    {
        $application = $contract->application;

        if (!$application) {
            return;
        }

        if ($contract->legal_representative_status === 'Rechazado') {
            $application->update(['status' => 'Rechazado']);
            return;
        }

        if ($contract->person_status === 'Desistió') {
            $application->update(['status' => 'Desistió']);
            return;
        }

        if (
            $contract->person_status === 'Firmado' &&
            $contract->legal_representative_status === 'Firmado'
        ) {
            $employee = Employee::withTrashed()->firstOrCreate(
                ['person_id' => $contract->person_id],
                [
                    'headquarter_id' => $contract->headquarter_id,
                    'position_id' => $contract->position_id,
                    'start_date' => $contract->start_date,
                    'end_date' => $contract->end_date,
                ]
            );

            if ($employee->trashed()) {
                $employee->restore();
            }

            $application->update(['status' => 'Vinculado']);
        }
    }

    protected function generate(Contract $contract)
    {
        $contract->load(['application' => ['vacant' => ['headquarter.entity' => ['person', 'enterprise']], 'person' => ['signature']]]);

        $html = '';
        foreach ($contract->settings?->documents ?? [] as &$document) {
            $data = (object)[
                'contract' => $contract
            ];
            foreach ($document->template?->settings?->sections ?? [] as &$section) {
                foreach ($section->subsections ?? [] as &$subsection) {
                    if (isset($subsection->items)) {
                        foreach ($subsection->items as &$item) {
                            $item->content = $this->renderTemplate($item->content, $document->variables, $data);
                        }
                    } else {
                        $subsection->content = $this->renderTemplate($subsection->content, $document->variables, $data);
                    }
                }
            }
            $settings = json_decode(json_encode($document->template->settings), true);
            $html .= view('Contract.ContractPreview', [
                'settings' => $settings,
                'template' => (array) $document->template
            ])->render();
        }

        $pdf = Pdf::loadHTML($html)->setOptions([
            'isRemoteEnabled' => true
        ]);

        $path = 'Contract/'.Str::random(40).'.pdf';

        Storage::disk('public')->put($path, $pdf->output());

        File::updateOrCreate(
            [
                'model_id' => $contract->id,
                'model_type' => Contract::class,
                'file_type_id' => 98,
                'file_subtype_id' => 131
            ],
            [
                'name' => $contract->uuid,
                'path' => $path,
                'mime' => 'application/pdf',
                'extension' => 'pdf',
                'size' => 0
            ]
        );
    }

    protected function renderTemplate(string $content, array $variablesConfig, object $data): string
    {
        $map = [];

        foreach ($variablesConfig as $variable) {
            $variable = (object) $variable;
            $basePath = $variable->path ?? null;
            $baseData = $basePath ? data_get($data, $basePath) : $data;
            foreach ($variable->groups as $group) {
                $group = (object) $group;
                $groupPath = $group->path ?? null;
                $groupData = $groupPath ? data_get($baseData, $groupPath) : $baseData;
                foreach ($group->fields as $field) {
                    $field = (object) $field;
                    $value = data_get($groupData, $field->field);
                    if (($field->type ?? null) === 'file' && !empty($value)) {
                        $map[$field->key] = '<img src="' . $value . '" class="img">';
                    } else {
                        $map[$field->key] = $value ?? '';
                    }
                }
            }
        }

        $text = preg_replace_callback('/<span class="var-chip"[^>]*data-key="([^"]+)"[^>]*>.*?<\/span>/s', fn ($match) => $map[$match[1]] ?? '', $content);
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = preg_replace('/\s+/', ' ', $text);
        return trim($text);
    }
}
