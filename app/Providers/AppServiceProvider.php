<?php

namespace App\Providers;

use App\Models\Contract;
use App\Models\CriminalRecord;
use App\Models\Entity;
use App\Models\ExamMedical;
use App\Models\ExamPolygraph;
use App\Models\InterviewInitial;
use App\Models\InterviewPsychotechnical;
use App\Models\InterviewVideo;
use App\Models\TestPsychotechnical;
use App\Models\TestTechnical;
use App\Observers\Contract\ContractObserver;
use App\Observers\Enterprise\Entity\EntityObserver;
use App\Observers\Vacant\Application\CriminalRecord\CriminalRecordObserver;
use App\Observers\Vacant\Application\ExamMedical\ExamMedicalObserver;
use App\Observers\Vacant\Application\ExamPolygraph\ExamPolygraphObserver;
use App\Observers\Vacant\Application\InterviewInitial\InterviewInitialObserver;
use App\Observers\Vacant\Application\InterviewPsychotechnical\InterviewPsychotechnicalObserver;
use App\Observers\Vacant\Application\InterviewVideo\InterviewVideoObserver;
use App\Observers\Vacant\Application\TestPsychotechnical\TestPsychotechnicalObserver;
use App\Observers\Vacant\Application\TestTechnical\TestTechnicalObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Entity::observe(EntityObserver::class);

        InterviewVideo::observe(InterviewVideoObserver::class);
        CriminalRecord::observe(CriminalRecordObserver::class);
        InterviewInitial::observe(InterviewInitialObserver::class);
        InterviewPsychotechnical::observe(InterviewPsychotechnicalObserver::class);

        TestTechnical::observe(TestTechnicalObserver::class);
        TestPsychotechnical::observe(TestPsychotechnicalObserver::class);

        ExamMedical::observe(ExamMedicalObserver::class);
        ExamPolygraph::observe(ExamPolygraphObserver::class);

        Contract::observe(ContractObserver::class);
    }
}
