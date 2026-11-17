<?php

declare(strict_types=1);

namespace App\Application\AbsenceTypeUseCase;

use App\Domain\AbsenceType\Contracts\AbsenceTypeContract;
use App\Http\Requests\AbsenceType\AbsenceTypeAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class AbsenceTypeAllUseCase
{
    private $absenceTypeContract;

    public function __construct(AbsenceTypeContract $absenceTypeContract)
    {
        $this->absenceTypeContract = $absenceTypeContract;
    }

    public function __invoke(AbsenceTypeAllRequest $request): LengthAwarePaginator
    {
        return $this->absenceTypeContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $request);
    }
}
