<?php

declare(strict_types=1);

namespace App\Domain\Person\Values;

final class PersonEmailVerifiedAt
{
    private $value;
    
    public function __construct(string|null $emailVerifiedAt)
    {
        $this->value = $emailVerifiedAt;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}