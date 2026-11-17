<?php

namespace App\Observers\Vacant\Application\TestPsychotechnical;

use App\Models\TestPsychotechnical;

class TestPsychotechnicalObserver
{
    /**
     * Handle the TestPsychotechnical "created" event.
     */
    public function created(TestPsychotechnical $testPsychotechnical): void
    {
        $this->applicationStatus($testPsychotechnical);
    }

    /**
     * Handle the TestPsychotechnical "updated" event.
     */
    public function updated(TestPsychotechnical $testPsychotechnical): void
    {
        $this->applicationStatus($testPsychotechnical);
    }

    /**
     * Handle the TestPsychotechnical "deleted" event.
     */
    public function deleted(TestPsychotechnical $testPsychotechnical): void
    {
        //
    }

    /**
     * Handle the TestPsychotechnical "restored" event.
     */
    public function restored(TestPsychotechnical $testPsychotechnical): void
    {
        //
    }

    /**
     * Handle the TestPsychotechnical "force deleted" event.
     */
    public function forceDeleted(TestPsychotechnical $testPsychotechnical): void
    {
        //
    }

    protected function applicationStatus(TestPsychotechnical $testPsychotechnical): void
    {
        $application = $testPsychotechnical->application;

        if (!$application) {
            return;
        }

        $testTechnical = $application->test_technical;

        $statuses = collect([
            $testPsychotechnical->psychologist_status,
            $testPsychotechnical->status,
            $testTechnical?->leader_status,
            $testTechnical?->status,
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
