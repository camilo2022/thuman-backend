<?php

namespace App\Observers\Vacant\Application\InterviewPsychotechnical;

use App\Models\InterviewPsychotechnical;

class InterviewPsychotechnicalObserver
{
    /**
     * Handle the InterviewPsychotechnical "created" event.
     */
    public function created(InterviewPsychotechnical $interviewPsychotechnical): void
    {
        $this->applicationStatus($interviewPsychotechnical);
    }

    /**
     * Handle the InterviewPsychotechnical "updated" event.
     */
    public function updated(InterviewPsychotechnical $interviewPsychotechnical): void
    {
        $this->applicationStatus($interviewPsychotechnical);
    }

    /**
     * Handle the InterviewPsychotechnical "deleted" event.
     */
    public function deleted(InterviewPsychotechnical $interviewPsychotechnical): void
    {
        //
    }

    /**
     * Handle the InterviewPsychotechnical "restored" event.
     */
    public function restored(InterviewPsychotechnical $interviewPsychotechnical): void
    {
        //
    }

    /**
     * Handle the InterviewPsychotechnical "force deleted" event.
     */
    public function forceDeleted(InterviewPsychotechnical $interviewPsychotechnical): void
    {
        //
    }

    protected function applicationStatus(InterviewPsychotechnical $interviewPsychotechnical): void
    {
        $application = $interviewPsychotechnical->application;

        if (!$application) {
            return;
        }

        $interviewInitial = $application->interview_initial;

        $statuses = collect([
            $interviewPsychotechnical->psychologist_status,
            $interviewPsychotechnical->status,
            $interviewInitial?->reviewer_status,
            $interviewInitial?->leader_status,
            $interviewInitial?->status,
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
