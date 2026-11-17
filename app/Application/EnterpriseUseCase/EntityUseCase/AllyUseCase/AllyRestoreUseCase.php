<?php

declare(strict_types=1);

namespace App\Application\EnterpriseUseCase\EntityUseCase\AllyUseCase;

use App\Domain\Enterprise\Entity\Ally\Ally;
use App\Domain\Enterprise\Entity\Ally\Contracts\AllyContract;
use App\Domain\Enterprise\Entity\Ally\Values\AllyId;

final class AllyRestoreUseCase
{
    private $allyContract;

    public function __construct(AllyContract $allyContract)
    {
        $this->allyContract = $allyContract;
    }

    public function __invoke(AllyId $allyId): ?Ally
    {
        return $this->allyContract->restore($allyId);
    }
}
