<?php

declare(strict_types=1);

namespace App\Application\VacantUseCase\ApplicationUseCase\ExamMedicalUseCase;

use App\Domain\Vacant\Application\ExamMedical\Contracts\ExamMedicalContract;
use App\Http\Requests\Vacant\Application\ExamMedical\ExamMedicalAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class ExamMedicalAllUseCase
{
    private $examMedicalContract;

    public function __construct(ExamMedicalContract $examMedicalContract)
    {
        $this->examMedicalContract = $examMedicalContract;
    }

    public function __invoke(ExamMedicalAllRequest $request): LengthAwarePaginator
    {
        return $this->examMedicalContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $request);
    }
}
