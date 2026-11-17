<?php

declare(strict_types=1);

namespace App\Domain\Person\Values;

final class PersonDocumentIssueCityId
{
    private $value;

    public function __construct(int|null $documentIssueCityId)
    {
        $this->value = $documentIssueCityId;
    }

    public function value(): int|null
    {
        return $this->value;
    }
}
