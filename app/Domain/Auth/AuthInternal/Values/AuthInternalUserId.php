<?php

declare(strict_types=1);

namespace App\Domain\Auth\AuthInternal\Values;

final class AuthInternalUserId
{
    private $value;

    public function __construct(int|null $userId)
    {
        $this->value = $userId;
    }

    public function value(): int|null
    {
        return $this->value;
    }
}
