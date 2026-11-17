<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\ExamPolygraph\Values;

final class ExamPolygraphResult
{
    private $value;

    public function __construct(string $result)
    {
        $this->value = $result;
    }

    public function value(): string
    {
        return $this->value;
    }
}
