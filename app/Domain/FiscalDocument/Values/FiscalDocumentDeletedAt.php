<?php

declare(strict_types=1);

namespace App\Domain\FiscalDocument\Values;

final class FiscalDocumentDeletedAt
{
    private $value;

    public function __construct(string|null $deletedAt)
    {
        $this->value = $deletedAt;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
