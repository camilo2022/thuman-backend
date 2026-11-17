<?php

declare(strict_types=1);

namespace App\Application\VacationUseCase;

use App\Domain\Vacation\Contracts\VacationContract;
use App\Http\Requests\Vacation\VacationAvailabilityRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class VacationAvailabilityUseCase
{
    private $vacationContract;

    public function __construct(VacationContract $vacationContract)
    {
        $this->vacationContract = $vacationContract;
    }

    public function __invoke(VacationAvailabilityRequest $request): object
    {
        return $this->vacationContract->availability($request->integer('person_id'));
    }
}
