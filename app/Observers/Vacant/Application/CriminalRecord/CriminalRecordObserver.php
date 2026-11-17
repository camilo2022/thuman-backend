<?php

namespace App\Observers\Vacant\Application\CriminalRecord;

use App\Models\CriminalRecord;

class CriminalRecordObserver
{
    /**
     * Handle the CriminalRecord "created" event.
     */
    public function created(CriminalRecord $criminalRecord): void
    {
        $this->applicationStatus($criminalRecord);
    }

    /**
     * Handle the CriminalRecord "updated" event.
     */
    public function updated(CriminalRecord $criminalRecord): void
    {
        $this->applicationStatus($criminalRecord);
    }

    /**
     * Handle the CriminalRecord "deleted" event.
     */
    public function deleted(CriminalRecord $criminalRecord): void
    {
        //
    }

    /**
     * Handle the CriminalRecord "restored" event.
     */
    public function restored(CriminalRecord $criminalRecord): void
    {
        //
    }

    /**
     * Handle the CriminalRecord "force deleted" event.
     */
    public function forceDeleted(CriminalRecord $criminalRecord): void
    {
        //
    }

    protected function applicationStatus(CriminalRecord $criminalRecord): void
    {
        $application = $criminalRecord->application;

        if (!$application) {
            return;
        }

        $requiredVideoInterview = $application->vacant->settings?->video_interview;

        $interviewVideo = $application->interview_video;

        $statuses = collect([
            $criminalRecord->status,
            $interviewVideo?->status ?? ($requiredVideoInterview ? 'Pendiente' : 'Aprobado'),
        ]);

        if ($statuses->contains(fn($s) => in_array($s, ['Rechazado', 'Desistió'], true))) {
            $application->update(['status' => 'Rechazado']);
            return;
        }

        if ($statuses->every(fn ($s) => in_array($s, ['Aprobado'], true))) {
            $application->update(['status' => 'En revisión']);
            return;
        }
    }
}
