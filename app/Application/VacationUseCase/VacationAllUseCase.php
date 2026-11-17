<?php

declare(strict_types=1);

namespace App\Application\VacationUseCase;

use App\Domain\Vacation\Contracts\VacationContract;
use App\Http\Requests\Vacation\VacationAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class VacationAllUseCase
{
    private $vacationContract;

    public function __construct(VacationContract $vacationContract)
    {
        $this->vacationContract = $vacationContract;
    }

    public function __invoke(VacationAllRequest $request): LengthAwarePaginator
    {
        return $this->vacationContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $request);
    }
}
