<?php

namespace App\Observers\Vacant\Application\ExamPolygraph;

use App\Models\ExamPolygraph;

class ExamPolygraphObserver
{
    /**
     * Handle the ExamPolygraph "created" event.
     */
    public function created(ExamPolygraph $examPolygraph): void
    {
        //
    }

    /**
     * Handle the ExamPolygraph "updated" event.
     */
    public function updated(ExamPolygraph $examPolygraph): void
    {
        //
    }

    /**
     * Handle the ExamPolygraph "deleted" event.
     */
    public function deleted(ExamPolygraph $examPolygraph): void
    {
        //
    }

    /**
     * Handle the ExamPolygraph "restored" event.
     */
    public function restored(ExamPolygraph $examPolygraph): void
    {
        //
    }

    /**
     * Handle the ExamPolygraph "force deleted" event.
     */
    public function forceDeleted(ExamPolygraph $examPolygraph): void
    {
        //
    }

    protected function applicationStatus(ExamPolygraph $examPolygraph): void
    {
        $application = $examPolygraph->application;

        if (!$application) {
            return;
        }

        $vacant = $application->vacant;
        $requiresPolygraph = $vacant?->settings?->polygraph ?? false;

        if (!$requiresPolygraph) {
            return;
        }

        $examMedical = $application->exam_medical;

        if (!$examMedical) {
            return;
        }

        $statuses = collect([
            $examMedical->status,
            $examPolygraph->status,
        ]);

        $results = collect([
            $examMedical->result,
            $examPolygraph->result,
        ]);

        if ($statuses->contains(fn($s) => in_array($s, ['Desistió', 'Rechazado'], true)) || $results->contains(fn($r) => in_array($r, ['No apto'], true))) {
            $application->update(['status' => 'Rechazado']);
            return;
        }

        $approvedStatuses = collect([
            $examMedical->status,
            $examPolygraph->status,
        ]);

        $approvedResults = collect([
            $examMedical->result,
            $examPolygraph->result,
        ]);

        if ($approvedStatuses->every(fn($s) => in_array($s, ['Realizado', 'Aprobado'], true)) && $approvedResults->every(fn($r) => in_array($r, ['Apto', 'Apto con recomendaciones', 'Aprobado'], true))) {
            $application->update(['status' => 'Contratación']);
            return;
        }
    }
}
