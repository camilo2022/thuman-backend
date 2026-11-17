<?php

declare(strict_types=1);

namespace App\Application\BankAccountTypeUseCase;

use App\Domain\BankAccountType\Contracts\BankAccountTypeContract;
use App\Http\Requests\BankAccountType\BankAccountTypeAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class BankAccountTypeAllUseCase
{
    private $bankAccountTypeContract;

    public function __construct(BankAccountTypeContract $bankAccountTypeContract)
    {
        $this->bankAccountTypeContract = $bankAccountTypeContract;
    }

    public function __invoke(BankAccountTypeAllRequest $request): LengthAwarePaginator
    {
        return $this->bankAccountTypeContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null);
    }
}
