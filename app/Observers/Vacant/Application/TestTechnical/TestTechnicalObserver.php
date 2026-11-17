<?php

namespace App\Observers\Vacant\Application\TestTechnical;

use App\Models\TestTechnical;

class TestTechnicalObserver
{
    /**
     * Handle the TestTechnical "created" event.
     */
    public function created(TestTechnical $testTechnical): void
    {
        $this->applicationStatus($testTechnical);
    }

    /**
     * Handle the TestTechnical "updated" event.
     */
    public function updated(TestTechnical $testTechnical): void
    {
        $this->applicationStatus($testTechnical);
    }

    /**
     * Handle the TestTechnical "deleted" event.
     */
    public function deleted(TestTechnical $testTechnical): void
    {
        //
    }

    /**
     * Handle the TestTechnical "restored" event.
     */
    public function restored(TestTechnical $testTechnical): void
    {
        //
    }

    /**
     * Handle the TestTechnical "force deleted" event.
     */
    public function forceDeleted(TestTechnical $testTechnical): void
    {
        //
    }

    protected function applicationStatus(TestTechnical $testTechnical): void
    {
        $application = $testTechnical->application;

        if (!$application) {
            return;
        }

        $testPsychotechnical = $application->test_psychotechnical;

        $statuses = collect([
            $testTechnical->leader_status,
            $testTechnical->status,
            $testPsychotechnical?->psychologist_status,
            $testPsychotechnical?->status,
        ]);

        if ($statuses->contains(fn ($s) => in_array($s, ['Expirado'], true))) {
            $application->update(['status' => 'Desistió']);
            return;
        }

        if ($statuses->contains(fn ($s) => in_array($s, ['Rechazado'], true))) {
            $application->update(['status' => 'Rechazado']);
            return;
        }

        if ($statuses->every(fn ($s) => in_array($s, ['Finalizado', 'Aprobado'], true))) {
            $application->update(['status' => 'Finalista']);
            return;
        }
    }
}
