<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\ExamPolygraph\Values;

final class FileName
{
    private $value;

    public function __construct(string $name)
    {
        $this->value = $name;
    }

    public function value(): string
    {
        return $this->value;
    }
}
