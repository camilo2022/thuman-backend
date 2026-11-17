<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\ExamMedical\Values;

final class ExamMedicalCityId
{
    private $value;

    public function __construct(int $cityId)
    {
        $this->value = $cityId;
    }

    public function value(): int
    {
        return $this->value;
    }
}
