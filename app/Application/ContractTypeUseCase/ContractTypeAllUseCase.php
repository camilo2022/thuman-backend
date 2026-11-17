<?php

declare(strict_types=1);

namespace App\Application\ContractTypeUseCase;

use App\Domain\ContractType\Contracts\ContractTypeContract;
use App\Http\Requests\ContractType\ContractTypeAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class ContractTypeAllUseCase
{
    private $contractTypeContract;

    public function __construct(ContractTypeContract $contractTypeContract)
    {
        $this->contractTypeContract = $contractTypeContract;
    }

    public function __invoke(ContractTypeAllRequest $request): LengthAwarePaginator
    {
        return $this->contractTypeContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $request);
    }
}
