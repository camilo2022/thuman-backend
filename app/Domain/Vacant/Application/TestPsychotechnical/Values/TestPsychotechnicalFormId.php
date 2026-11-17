<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\TestPsychotechnical\Values;

final class TestPsychotechnicalFormId
{
    private $value;

    public function __construct(int $formId)
    {
        $this->value = $formId;
    }

    public function value(): int
    {
        return $this->value;
    }
}
