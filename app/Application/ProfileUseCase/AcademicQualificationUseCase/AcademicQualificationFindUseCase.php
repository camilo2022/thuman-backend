<?php

declare(strict_types=1);

namespace App\Application\ProfileUseCase\AcademicQualificationUseCase;

use App\Domain\Profile\AcademicQualification\AcademicQualification;
use App\Domain\Profile\AcademicQualification\Contracts\AcademicQualificationContract;
use App\Domain\Profile\AcademicQualification\Values\AcademicQualificationId;

final class AcademicQualificationFindUseCase
{
    private $academicQualificationContract;

    public function __construct(AcademicQualificationContract $academicQualificationContract)
    {
        $this->academicQualificationContract = $academicQualificationContract;
    }

    public function __invoke(AcademicQualificationId $academicQualificationId): ?AcademicQualification
    {
        return $this->academicQualificationContract->find($academicQualificationId);
    }
}
