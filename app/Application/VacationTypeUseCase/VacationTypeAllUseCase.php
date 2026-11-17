<?php

declare(strict_types=1);

namespace App\Application\VacationTypeUseCase;

use App\Domain\VacationType\Contracts\VacationTypeContract;
use App\Http\Requests\VacationType\VacationTypeAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class VacationTypeAllUseCase
{
    private $vacationTypeContract;

    public function __construct(VacationTypeContract $vacationTypeContract)
    {
        $this->vacationTypeContract = $vacationTypeContract;
    }

    public function __invoke(VacationTypeAllRequest $request): LengthAwarePaginator
    {
        return $this->vacationTypeContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $request);
    }
}
