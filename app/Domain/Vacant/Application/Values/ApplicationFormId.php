<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\Values;

final class ApplicationFormId
{
    private $value;

    public function __construct(int|null $formId)
    {
        $this->value = $formId;
    }

    public function value(): int|null
    {
        return $this->value;
    }
}
