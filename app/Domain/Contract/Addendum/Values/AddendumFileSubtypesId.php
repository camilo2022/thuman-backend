<?php

declare(strict_types=1);

namespace App\Domain\Contract\Addendum\Values;

final class AddendumFileSubtypesId
{
    private $value;

    public function __construct(object|array|null $fileSubtypesId = [])
    {
        if (is_null($fileSubtypesId)) {
            $fileSubtypesId = [];
        }

        if (is_object($fileSubtypesId)) {
            $fileSubtypesId = (array) $fileSubtypesId;
        }

        $this->value = $fileSubtypesId;
    }

    public function value(): array|null
    {
        return $this->value;
    }
}
