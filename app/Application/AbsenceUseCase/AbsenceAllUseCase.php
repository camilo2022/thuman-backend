<?php

declare(strict_types=1);

namespace App\Application\AbsenceUseCase;

use App\Domain\Absence\Contracts\AbsenceContract;
use App\Http\Requests\Absence\AbsenceAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class AbsenceAllUseCase
{
    private $absenceContract;

    public function __construct(AbsenceContract $absenceContract)
    {
        $this->absenceContract = $absenceContract;
    }

    public function __invoke(AbsenceAllRequest $request): LengthAwarePaginator
    {
        return $this->absenceContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $request);
    }
}
