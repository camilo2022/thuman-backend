<?php

declare(strict_types=1);

namespace App\Domain\Survey\Values;

final class SurveyQuestions
{
    private $value;

    public function __construct(object|array|string $questions)
    {
        if (is_string($questions)) {
            $questions = json_decode($questions, true);
        }

        if (is_object($questions)) {
            $questions = (array) $questions;
        }

        $this->value = $questions;
    }

    public function value(): array
    {
        return $this->value;
    }
}
