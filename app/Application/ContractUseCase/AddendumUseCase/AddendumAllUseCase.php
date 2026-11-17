<?php

declare(strict_types=1);

namespace App\Application\ContractUseCase\AddendumUseCase;

use App\Domain\Contract\Addendum\Contracts\AddendumContract;
use App\Domain\Contract\Values\ContractId;
use App\Http\Requests\Contract\Addendum\AddendumAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class AddendumAllUseCase
{
    private $addendumContract;

    public function __construct(AddendumContract $addendumContract)
    {
        $this->addendumContract = $addendumContract;
    }

    public function __invoke(AddendumAllRequest $request, ContractId $contractId): LengthAwarePaginator
    {
        return $this->addendumContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $request, $contractId);
    }
}
