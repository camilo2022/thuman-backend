<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\ExamMedical\Values;

final class ExamMedicalHealthEntityId
{
    private $value;

    public function __construct(int $healthEntityId)
    {
        $this->value = $healthEntityId;
    }

    public function value(): int
    {
        return $this->value;
    }
}
