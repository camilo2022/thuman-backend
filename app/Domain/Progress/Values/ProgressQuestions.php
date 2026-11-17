<?php

declare(strict_types=1);

namespace App\Domain\Progress\Values;

final class ProgressQuestions
{
    private $value;

    public function __construct(object|array|string|null $questions)
    {
        if (is_null($questions)) {
            $questions = [];
        }

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
