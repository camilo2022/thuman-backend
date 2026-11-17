<?php

declare(strict_types=1);

namespace App\Application\HiringReasonUseCase;

use App\Domain\HiringReason\Contracts\HiringReasonContract;
use App\Http\Requests\HiringReason\HiringReasonAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class HiringReasonAllUseCase
{
    private $hiringReasonContract;

    public function __construct(HiringReasonContract $hiringReasonContract)
    {
        $this->hiringReasonContract = $hiringReasonContract;
    }

    public function __invoke(HiringReasonAllRequest $request): LengthAwarePaginator
    {
        return $this->hiringReasonContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null);
    }
}
