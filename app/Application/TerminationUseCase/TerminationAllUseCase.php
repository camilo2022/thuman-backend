<?php

declare(strict_types=1);

namespace App\Application\TerminationUseCase;

use App\Domain\Termination\Contracts\TerminationContract;
use App\Http\Requests\Termination\TerminationAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class TerminationAllUseCase
{
    private $terminationContract;

    public function __construct(TerminationContract $terminationContract)
    {
        $this->terminationContract = $terminationContract;
    }

    public function __invoke(TerminationAllRequest $request): LengthAwarePaginator
    {
        return $this->terminationContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $request);
    }
}
