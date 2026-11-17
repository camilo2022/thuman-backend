<?php

declare(strict_types=1);

namespace App\Application\ProgressUseCase;

use App\Domain\Progress\Progress;
use App\Domain\Progress\Values\ProgressId;
use App\Domain\Progress\Contracts\ProgressContract;
use App\Http\Requests\Progress\ProgressFindRequest;

final class ProgressFindUseCase
{
    private $progressContract;

    public function __construct(ProgressContract $progressContract)
    {
        $this->progressContract = $progressContract;
    }

    public function __invoke(ProgressFindRequest $request, ProgressId $progressId): ?Progress
    {
        return $this->progressContract->find($request, $progressId);
    }
}
