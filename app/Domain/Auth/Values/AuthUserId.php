<?php

declare(strict_types=1);

namespace App\Domain\Auth\Values;

final class AuthUserId
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