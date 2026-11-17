<?php

declare(strict_types=1);

namespace App\Domain\Person\Values;

final class PersonPhoneNumberVerifiedAt
{
    private $value;
    
    public function __construct(string|null $phoneNumberVerifiedAt)
    {
        $this->value = $phoneNumberVerifiedAt;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}