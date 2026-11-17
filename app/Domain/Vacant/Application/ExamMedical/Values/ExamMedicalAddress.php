<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\ExamMedical\Values;

final class ExamMedicalAddress
{
    private $value;

    public function __construct(string $address)
    {
        $this->value = $address;
    }

    public function value(): string
    {
        return $this->value;
    }
}
