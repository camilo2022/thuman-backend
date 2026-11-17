<?php

declare(strict_types=1);

namespace App\Application\AcademicLevelUseCase;

use App\Domain\AcademicLevel\AcademicLevel;
use App\Domain\AcademicLevel\Contracts\AcademicLevelContract;
use App\Domain\AcademicLevel\Values\AcademicLevelId;

final class AcademicLevelFindUseCase
{
    private $academicLevelContract;

    public function __construct(AcademicLevelContract $academicLevelContract)
    {
        $this->academicLevelContract = $academicLevelContract;
    }

    public function __invoke(AcademicLevelId $academicLevelId): ?AcademicLevel
    {
        return $this->academicLevelContract->find($academicLevelId);
    }
}
