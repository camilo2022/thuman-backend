<?php

namespace App\Observers\Vacant\Application\ExamMedical;

use App\Models\ExamMedical;

class ExamMedicalObserver
{
    /**
     * Handle the ExamMedical "created" event.
     */
    public function created(ExamMedical $examMedical): void
    {
        $this->applicationStatus($examMedical);
    }

    /**
     * Handle the ExamMedical "updated" event.
     */
    public function updated(ExamMedical $examMedical): void
    {
        $this->applicationStatus($examMedical);
    }

    /**
     * Handle the ExamMedical "deleted" event.
     */
    public function deleted(ExamMedical $examMedical): void
    {
        //
    }

    /**
     * Handle the ExamMedical "restored" event.
     */
    public function restored(ExamMedical $examMedical): void
    {
        //
    }

    /**
     * Handle the ExamMedical "force deleted" event.
     */
    public function forceDeleted(ExamMedical $examMedical): void
    {
        //
    }

    protected function applicationStatus(ExamMedical $examMedical): void
    {
        $application = $examMedical->application;

        if (!$application) {
            return;
        }

        $vacant = $application->vacant;
        $requiredPolygraph = $vacant?->settings?->polygraph ?? false;

        $examPolygraph = $application->exam_polygraph;

        if ($requiredPolygraph && !$examPolygraph) {
            return;
        }

        $statuses = collect([
            $examMedical->status,
            $requiredPolygraph ? ($examPolygraph?->status) : 'Realizado',
        ]);

        $results = collect([
            $examMedical->result,
            $requiredPolygraph ? ($examPolygraph?->result) : 'Aprobado',
        ]);

        if ($statuses->contains(fn($s) => in_array($s, ['Desistió', 'Rechazado'], true)) || $results->contains(fn($r) => in_array($r, ['No apto'], true))) {
            $application->update(['status' => 'Rechazado']);
            return;
        }

        // $approvedStatuses = collect([
        //     $examMedical->status,
        //     $requiredPolygraph ? ($examPolygraph?->status) : 'Realizado',
        // ]);

        // $approvedResults = collect([
        //     $examMedical->result,
        //     $requiredPolygraph ? ($examPolygraph?->result) : 'Apto',
        // ]);

        // if ($approvedStatuses->every(fn($s) => in_array($s, ['Realizado', 'Aprobado'], true)) && $approvedResults->every(fn($r) => in_array($r, ['Apto', 'Apto con recomendaciones', 'Aprobado'], true))) {
        //     $application->update(['status' => 'Contratación']);
        //     return;
        // }
    }
}
