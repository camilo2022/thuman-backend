<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Values;

final class VacantBenefitsId
{
    private $value;

    public function __construct(object|array|null $benefitsId = [])
    {
        if (is_null($benefitsId)) {
            $benefitsId = [];
        }

        if (is_object($benefitsId)) {
            $benefitsId = (array) $benefitsId;
        }

        $this->value = $benefitsId;
    }

    public function value(): array|null
    {
        return $this->value;
    }
}
