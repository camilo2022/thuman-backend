<?php

declare(strict_types=1);

namespace App\Application\TerminationTypeUseCase;

use App\Domain\TerminationType\Contracts\TerminationTypeContract;
use App\Http\Requests\TerminationType\TerminationTypeAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class TerminationTypeAllUseCase
{
    private $terminationTypeContract;

    public function __construct(TerminationTypeContract $terminationTypeContract)
    {
        $this->terminationTypeContract = $terminationTypeContract;
    }

    public function __invoke(TerminationTypeAllRequest $request): LengthAwarePaginator
    {
        return $this->terminationTypeContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $request);
    }
}
