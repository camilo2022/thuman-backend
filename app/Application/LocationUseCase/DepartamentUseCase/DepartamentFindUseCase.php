<?php

declare(strict_types=1);

namespace App\Application\LocationUseCase\DepartamentUseCase;

use App\Domain\Location\Departament\Contracts\DepartamentContract;
use App\Domain\Location\Departament\Departament;
use App\Domain\Location\Departament\Values\DepartamentId;
use App\Http\Requests\Location\Departament\DepartamentFindRequest;

final class DepartamentFindUseCase
{
    private $departamentContract;

    public function __construct(DepartamentContract $departamentContract)
    {
        $this->departamentContract = $departamentContract;
    }

    public function __invoke(DepartamentFindRequest $request, DepartamentId $regionId): ?Departament
    {
        return $this->departamentContract->find($request, $regionId);
    }
}
