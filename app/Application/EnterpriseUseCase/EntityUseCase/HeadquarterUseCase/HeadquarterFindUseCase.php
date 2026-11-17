<?php

declare(strict_types=1);

namespace App\Application\EnterpriseUseCase\EntityUseCase\HeadquarterUseCase;

use App\Domain\Enterprise\Entity\Headquarter\Headquarter;
use App\Domain\Enterprise\Entity\Headquarter\Contracts\HeadquarterContract;
use App\Domain\Enterprise\Entity\Headquarter\Values\HeadquarterId;

final class HeadquarterFindUseCase
{
    private $headquarterContract;

    public function __construct(HeadquarterContract $headquarterContract)
    {
        $this->headquarterContract = $headquarterContract;
    }

    public function __invoke(HeadquarterId $headquarterId): ?Headquarter
    {
        return $this->headquarterContract->find($headquarterId);
    }
}
