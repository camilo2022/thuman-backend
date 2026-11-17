<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\InterviewVideo\Values;

final class InterviewVideoQuestions
{
    private $value;

    public function __construct(object|array|string $questions)
    {
        if (is_string($questions)) {
            $questions = json_decode($questions, true);
        }

        $this->value = $questions;
    }

    public function value(): object|array
    {
        return $this->value;
    }
}
