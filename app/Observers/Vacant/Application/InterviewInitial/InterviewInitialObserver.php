<?php

namespace App\Observers\Vacant\Application\InterviewInitial;

use App\Models\InterviewInitial;

class InterviewInitialObserver
{
    /**
     * Handle the InterviewInitial "created" event.
     */
    public function created(InterviewInitial $interviewInitial): void
    {
        $this->applicationStatus($interviewInitial);
    }

    /**
     * Handle the InterviewInitial "updated" event.
     */
    public function updated(InterviewInitial $interviewInitial): void
    {
        $this->applicationStatus($interviewInitial);
    }

    /**
     * Handle the InterviewInitial "deleted" event.
     */
    public function deleted(InterviewInitial $interviewInitial): void
    {
        //
    }

    /**
     * Handle the InterviewInitial "restored" event.
     */
    public function restored(InterviewInitial $interviewInitial): void
    {
        //
    }

    /**
     * Handle the InterviewInitial "force deleted" event.
     */
    public function forceDeleted(InterviewInitial $interviewInitial): void
    {
        //
    }

    protected function applicationStatus(InterviewInitial $interviewInitial): void
    {
        $application = $interviewInitial->application;

        if (!$application) {
            return;
        }

        $interviewPsychotechnical = $application->interview_psychotechnical;

        $statuses = collect([
            $interviewInitial->reviewer_status,
            $interviewInitial->leader_status,
            $interviewInitial->status,
            $interviewPsychotechnical?->psychologist_status,
            $interviewPsychotechnical?->status,
        ]);

        if ($statuses->contains(fn ($s) => in_array($s, ['Desistió'], true))) {
            $application->update(['status' => 'Desistió']);
            return;
        }

        if ($statuses->contains(fn ($s) => in_array($s, ['Rechazado'], true))) {
            $application->update(['status' => 'Rechazado']);
            return;
        }

        if ($statuses->every(fn ($s) => in_array($s, ['Realizado', 'Aprobado'], true))) {
            $application->update(['status' => 'Prueba']);
            return;
        }
    }
}
