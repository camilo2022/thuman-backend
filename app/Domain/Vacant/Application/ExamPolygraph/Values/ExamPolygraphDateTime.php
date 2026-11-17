<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\ExamPolygraph\Values;

final class ExamPolygraphDateTime
{
    private $value;

    public function __construct(string $dateTime)
    {
        $this->value = $dateTime;
    }

    public function value(): string
    {
        return $this->value;
    }
}
