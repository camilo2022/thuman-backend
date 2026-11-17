<?php

declare(strict_types=1);

namespace App\Application\VacantUseCase\ApplicationUseCase\ExamMedicalUseCase;

use App\Domain\Vacant\Application\ExamMedical\ExamMedical;
use App\Domain\Vacant\Application\ExamMedical\Values\ExamMedicalId;
use App\Domain\Vacant\Application\ExamMedical\Contracts\ExamMedicalContract;
use App\Http\Requests\Vacant\Application\ExamMedical\ExamMedicalFindRequest;

final class ExamMedicalFindUseCase
{
    private $examMedicalContract;

    public function __construct(ExamMedicalContract $examMedicalContract)
    {
        $this->examMedicalContract = $examMedicalContract;
    }

    public function __invoke(ExamMedicalFindRequest $request, ExamMedicalId $examMedicalId): ?ExamMedical
    {
        return $this->examMedicalContract->find($request, $examMedicalId);
    }
}
