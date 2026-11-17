<?php

declare(strict_types=1);

namespace App\Domain\User\Values;

use Illuminate\Support\Facades\Hash;

final class UserPassword
{
    private $value;

    public function __construct(string $password)
    {
        $this->value = $password;
    }

    public function value(): string
    {
        return Hash::needsRehash($this->value) ? Hash::make($this->value) : $this->value;
    }
}
