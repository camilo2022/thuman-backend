<?php

namespace App\Jobs\Vacant\Application\TestPsychotechnical;

use App\Models\TestPsychotechnical;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TestPsychotechnicalExpiredJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
        //
    }

    public function handle(): void
    {
        TestPsychotechnical::whereIn('status', [TestPsychotechnical::PENDING, TestPsychotechnical::INPROGRESS])
            ->whereDate('end_date', '<', Carbon::now()->toDateString())
            ->update(['status' => TestPsychotechnical::EXPIRED]);
    }
}
