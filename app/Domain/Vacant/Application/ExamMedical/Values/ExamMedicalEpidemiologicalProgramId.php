<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\ExamMedical\Values;

final class ExamMedicalEpidemiologicalProgramId
{
    private $value;

    public function __construct(int|null $EpidemiologicalProgramId)
    {
        $this->value = $EpidemiologicalProgramId;
    }

    public function value(): int|null
    {
        return $this->value;
    }
}
