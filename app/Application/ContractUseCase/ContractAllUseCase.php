<?php

declare(strict_types=1);

namespace App\Application\ContractUseCase;

use App\Domain\Contract\Contracts\ContractContract;
use App\Http\Requests\Contract\ContractAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class ContractAllUseCase
{
    private $contractContract;

    public function __construct(ContractContract $contractContract)
    {
        $this->contractContract = $contractContract;
    }

    public function __invoke(ContractAllRequest $request): LengthAwarePaginator
    {
        return $this->contractContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $request);
    }
}
