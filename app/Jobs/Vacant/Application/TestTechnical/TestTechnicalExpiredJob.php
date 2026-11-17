<?php

namespace App\Jobs\Vacant\Application\TestTechnical;

use App\Models\TestTechnical;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TestTechnicalExpiredJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
        //
    }

    public function handle(): void
    {
        TestTechnical::whereIn('status', [TestTechnical::PENDING, TestTechnical::INPROGRESS])
            ->whereDate('end_date', '<', Carbon::now()->toDateString())
            ->update(['status' => TestTechnical::EXPIRED]);
    }
}
