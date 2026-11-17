<?php

declare(strict_types=1);

namespace App\Domain\Enterprise\Entity\Template\Values;

final class TemplateId
{
    private $value;

    public function __construct(int|null $id)
    {
        $this->value = $id;
    }

    public function value(): int|null
    {
        return $this->value;
    }
}
