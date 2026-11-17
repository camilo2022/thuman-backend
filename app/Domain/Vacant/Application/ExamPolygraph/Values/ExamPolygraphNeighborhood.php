<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\ExamPolygraph\Values;

final class ExamPolygraphNeighborhood
{
    private $value;

    public function __construct(string $neighborhood)
    {
        $this->value = $neighborhood;
    }

    public function value(): string
    {
        return $this->value;
    }
}
