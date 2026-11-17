<?php

declare(strict_types=1);

namespace App\Application\PersonTypeUseCase;

use App\Domain\PersonType\Contracts\PersonTypeContract;
use App\Http\Requests\PersonType\PersonTypeAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class PersonTypeAllUseCase
{
    private $personTypeContract;

    public function __construct(PersonTypeContract $personTypeContract)
    {
        $this->personTypeContract = $personTypeContract;
    }

    public function __invoke(PersonTypeAllRequest $request): LengthAwarePaginator
    {
        return $this->personTypeContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null);
    }
}
