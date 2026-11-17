<?php

namespace App\Observers\Vacant\Application\InterviewVideo;

use App\Models\InterviewVideo;

class InterviewVideoObserver
{
    /**
     * Handle the InterviewVideo "created" event.
     */
    public function created(InterviewVideo $interviewVideo): void
    {
        $this->applicationStatus($interviewVideo);
    }

    /**
     * Handle the InterviewVideo "updated" event.
     */
    public function updated(InterviewVideo $interviewVideo): void
    {
        $this->applicationStatus($interviewVideo);
    }

    /**
     * Handle the InterviewVideo "deleted" event.
     */
    public function deleted(InterviewVideo $interviewVideo): void
    {
        //
    }

    /**
     * Handle the InterviewVideo "restored" event.
     */
    public function restored(InterviewVideo $interviewVideo): void
    {
        //
    }

    /**
     * Handle the InterviewVideo "force deleted" event.
     */
    public function forceDeleted(InterviewVideo $interviewVideo): void
    {
        //
    }

    protected function applicationStatus(InterviewVideo $interviewVideo): void
    {
        $application = $interviewVideo->application;

        if (!$application) {
            return;
        }

        $criminalRecord = $application->criminal_record;

        $statuses = collect([
            $interviewVideo->status,
            $criminalRecord?->status,
        ]);

        if ($statuses->contains(fn ($s) => in_array($s, ['Rechazado', 'Desistió'], true))) {
            $application->update(['status' => 'Rechazado']);
            return;
        }

        if ($statuses->every(fn ($s) => in_array($s, ['Aprobado'], true))) {
            $application->update(['status' => 'En revisión']);
            return;
        }
    }
}
