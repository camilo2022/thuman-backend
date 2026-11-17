<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Jobs\Vacant\Application\TestTechnical\TestTechnicalExpiredJob;
use App\Jobs\Vacant\Application\TestPsychotechnical\TestPsychotechnicalExpiredJob;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::job(new TestTechnicalExpiredJob())->dailyAt('00:00');

Schedule::job(new TestPsychotechnicalExpiredJob())->dailyAt('00:00');
