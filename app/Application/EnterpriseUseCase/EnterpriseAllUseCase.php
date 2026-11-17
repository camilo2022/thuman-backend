<?php

declare(strict_types=1);

namespace App\Application\EnterpriseUseCase;

use App\Domain\Enterprise\Contracts\EnterpriseContract;
use App\Http\Requests\Enterprise\EnterpriseAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class EnterpriseAllUseCase
{
    private $enterpriseContract;

    public function __construct(EnterpriseContract $enterpriseContract)
    {
        $this->enterpriseContract = $enterpriseContract;
    }

    public function __invoke(EnterpriseAllRequest $request): LengthAwarePaginator
    {
        return $this->enterpriseContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $request);
    }
}
