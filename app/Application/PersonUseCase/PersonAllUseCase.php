<?php

declare(strict_types=1);

namespace App\Application\PersonUseCase;

use App\Domain\Person\Contracts\PersonContract;
use App\Http\Requests\Person\PersonAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class PersonAllUseCase
{
    private $personContract;

    public function __construct(PersonContract $personContract)
    {
        $this->personContract = $personContract;
    }

    public function __invoke(PersonAllRequest $request): LengthAwarePaginator
    {
        return $this->personContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $request);
    }
}
