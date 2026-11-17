<?php

declare(strict_types=1);

namespace App\Application\MaritalStatusUseCase;

use App\Domain\MaritalStatus\Contracts\MaritalStatusContract;
use App\Http\Requests\MaritalStatus\MaritalStatusAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class MaritalStatusAllUseCase
{
    private $maritalStatusContract;

    public function __construct(MaritalStatusContract $maritalStatusContract)
    {
        $this->maritalStatusContract = $maritalStatusContract;
    }

    public function __invoke(MaritalStatusAllRequest $request): LengthAwarePaginator
    {
        return $this->maritalStatusContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $request);
    }
}
