<?php

declare(strict_types=1);

namespace App\Domain\Auth\AuthInternal\Values;

final class AuthInternalToken
{
    private $value;

    public function __construct(string|null $token)
    {
        $this->value = $token;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
