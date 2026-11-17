<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\Values;

final class ApplicationAnswers
{
    private $value;

    public function __construct(object|array|string|null $answers)
    {
        if (is_null($answers)) {
            $answers = [];
        }

        if (is_string($answers)) {
            $answers = json_decode($answers, true);
        }

        $this->value = $answers;
    }

    public function value(): object|array
    {
        return $this->value;
    }
}
