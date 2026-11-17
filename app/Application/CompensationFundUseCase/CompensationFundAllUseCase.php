<?php

declare(strict_types=1);

namespace App\Application\CompensationFundUseCase;

use App\Domain\CompensationFund\Contracts\CompensationFundContract;
use App\Http\Requests\CompensationFund\CompensationFundAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class CompensationFundAllUseCase
{
    private $compensationFundContract;

    public function __construct(CompensationFundContract $compensationFundContract)
    {
        $this->compensationFundContract = $compensationFundContract;
    }

    public function __invoke(CompensationFundAllRequest $request): LengthAwarePaginator
    {
        return $this->compensationFundContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $request);
    }
}
