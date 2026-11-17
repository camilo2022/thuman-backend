<?php

declare(strict_types=1);

namespace App\Application\BloodTypeUseCase;

use App\Domain\BloodType\BloodType;
use App\Domain\BloodType\Contracts\BloodTypeContract;
use App\Domain\BloodType\Values\BloodTypeId;

final class BloodTypeFindUseCase
{
    private $bloodTypeContract;

    public function __construct(BloodTypeContract $bloodTypeContract)
    {
        $this->bloodTypeContract = $bloodTypeContract;
    }

    public function __invoke(BloodTypeId $bloodTypeId): ?BloodType
    {
        return $this->bloodTypeContract->find($bloodTypeId);
    }
}
