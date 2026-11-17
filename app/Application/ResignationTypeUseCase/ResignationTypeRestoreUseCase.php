<?php

declare(strict_types=1);

namespace App\Application\ResignationTypeUseCase;

use App\Domain\ResignationType\ResignationType;
use App\Domain\ResignationType\Contracts\ResignationTypeContract;
use App\Domain\ResignationType\Values\ResignationTypeId;

final class ResignationTypeRestoreUseCase
{
    private $resignationTypeContract;

    public function __construct(ResignationTypeContract $resignationTypeContract)
    {
        $this->resignationTypeContract = $resignationTypeContract;
    }

    public function __invoke(ResignationTypeId $resignationTypeId): ?ResignationType
    {
        return $this->resignationTypeContract->restore($resignationTypeId);
    }
}
