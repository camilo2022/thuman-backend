<?php

declare(strict_types=1);

namespace App\Application\LocationUseCase\DepartamentUseCase;

use App\Domain\Location\Departament\Contracts\DepartamentContract;
use App\Http\Requests\Location\Departament\DepartamentAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class DepartamentAllUseCase
{
    private $departamentContract;

    public function __construct(DepartamentContract $departamentContract)
    {
        $this->departamentContract = $departamentContract;
    }

    public function __invoke(DepartamentAllRequest $request): LengthAwarePaginator
    {
        return $this->departamentContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $request);
    }
}
