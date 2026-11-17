<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\ExamPolygraph\Values;

final class ExamPolygraphServiceProviderId
{
    private $value;

    public function __construct(int $serviceProviderId)
    {
        $this->value = $serviceProviderId;
    }

    public function value(): int
    {
        return $this->value;
    }
}
