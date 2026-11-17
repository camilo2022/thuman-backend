<?php

declare(strict_types=1);

namespace App\Domain\Survey\Values;

final class SurveyAnswers
{
    private $value;

    public function __construct(object|array|string $answers)
    {
        if (is_string($answers)) {
            $answers = json_decode($answers, true);
        }

        if (is_object($answers)) {
            $answers = (array) $answers;
        }

        $this->value = $answers;
    }

    public function value(): array
    {
        return $this->value;
    }
}
