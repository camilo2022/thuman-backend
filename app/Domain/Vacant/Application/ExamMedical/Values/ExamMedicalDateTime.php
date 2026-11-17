<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\ExamMedical\Values;

final class ExamMedicalDateTime
{
    private $value;

    public function __construct(string $dateTime)
    {
        $this->value = $dateTime;
    }

    public function value(): string
    {
        return $this->value;
    }
}
