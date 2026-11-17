<?php

declare(strict_types=1);

namespace App\Application\TerminationTypeUseCase\ReasonUseCase;

use App\Domain\TerminationType\Reason\Contracts\ReasonContract;
use App\Domain\TerminationType\Values\TerminationTypeId;
use App\Http\Requests\TerminationType\Reason\ReasonAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class ReasonAllUseCase
{
    private $reasonContract;

    public function __construct(ReasonContract $reasonContract)
    {
        $this->reasonContract = $reasonContract;
    }

    public function __invoke(ReasonAllRequest $request, TerminationTypeId $terminationTypeId): LengthAwarePaginator
    {
        return $this->reasonContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $request, $terminationTypeId);
    }
}
