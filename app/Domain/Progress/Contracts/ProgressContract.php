<?php

declare(strict_types=1);

namespace App\Domain\Progress\Contracts;

use App\Domain\Progress\Progress;
use App\Domain\Progress\Values\ProgressId;
use App\Http\Requests\Progress\ProgressFindRequest;

interface ProgressContract
{
    public function find(ProgressFindRequest $request, ProgressId $progressId): ?Progress;

    public function store(Progress $progress): Progress;

    public function update(Progress $progress, ProgressId $progressId): Progress;
}
