<?php

declare(strict_types=1);

namespace App\Application\ServiceTypeUseCase;

use App\Domain\ServiceType\ServiceType;
use App\Domain\ServiceType\Contracts\ServiceTypeContract;
use App\Domain\ServiceType\Values\ServiceTypeId;

final class ServiceTypeFindUseCase
{
    private $serviceTypeContract;

    public function __construct(ServiceTypeContract $serviceTypeContract)
    {
        $this->serviceTypeContract = $serviceTypeContract;
    }

    public function __invoke(ServiceTypeId $serviceTypeId): ?ServiceType
    {
        return $this->serviceTypeContract->find($serviceTypeId);
    }
}
