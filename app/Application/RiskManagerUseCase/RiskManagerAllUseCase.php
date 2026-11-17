<?php

declare(strict_types=1);

namespace App\Application\RiskManagerUseCase;

use App\Domain\RiskManager\Contracts\RiskManagerContract;
use App\Http\Requests\RiskManager\RiskManagerAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class RiskManagerAllUseCase
{
    private $riskManagerContract;

    public function __construct(RiskManagerContract $riskManagerContract)
    {
        $this->riskManagerContract = $riskManagerContract;
    }

    public function __invoke(RiskManagerAllRequest $request): LengthAwarePaginator
    {
        return $this->riskManagerContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $request);
    }
}
