<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\ExamMedical\Values;

final class ExamMedicalIndication
{
    private $value;

    public function __construct(string|null $indication)
    {
        $this->value = $indication;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
