<?php

declare(strict_types=1);

namespace App\Domain\Survey\Values;

final class SurveyFormId
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
