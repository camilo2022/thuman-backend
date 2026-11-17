<?php

declare(strict_types=1);

namespace App\Application\PositionUseCase;

use App\Domain\Position\Position;
use App\Domain\Position\Contracts\PositionContract;
use App\Domain\Position\Values\PositionId;

final class PositionDeleteUseCase
{
    private $positionContract;

    public function __construct(PositionContract $positionContract)
    {
        $this->positionContract = $positionContract;
    }

    public function __invoke(PositionId $positionId): ?Position
    {
        return $this->positionContract->delete($positionId);
    }
}
