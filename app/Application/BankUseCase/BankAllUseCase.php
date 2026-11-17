<?php

declare(strict_types=1);

namespace App\Application\BankUseCase;

use App\Domain\Bank\Contracts\BankContract;
use App\Http\Requests\Bank\BankAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class BankAllUseCase
{
    private $bankContract;

    public function __construct(BankContract $bankContract)
    {
        $this->bankContract = $bankContract;
    }

    public function __invoke(BankAllRequest $request): LengthAwarePaginator
    {
        return $this->bankContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $request);
    }
}
