<?php

declare(strict_types=1);

namespace App\Domain\Auth\AuthInternal\Values;

final class AuthInternalDocumentNumber
{
    private $value;

    public function __construct(string|null $documentNumber)
    {
        $this->value = $documentNumber;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
