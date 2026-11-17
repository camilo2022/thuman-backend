<?php

declare(strict_types=1);

namespace App\Application\SizeUseCase;

use App\Domain\Size\Size;
use App\Domain\Size\Contracts\SizeContract;
use App\Domain\Size\Values\SizeId;

final class SizeFindUseCase
{
    private $sizeContract;

    public function __construct(SizeContract $sizeContract)
    {
        $this->sizeContract = $sizeContract;
    }

    public function __invoke(SizeId $sizeId): ?Size
    {
        return $this->sizeContract->find($sizeId);
    }
}
