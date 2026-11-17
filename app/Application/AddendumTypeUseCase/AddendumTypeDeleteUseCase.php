<?php

declare(strict_types=1);

namespace App\Application\AddendumTypeUseCase;

use App\Domain\AddendumType\AddendumType;
use App\Domain\AddendumType\Contracts\AddendumTypeContract;
use App\Domain\AddendumType\Values\AddendumTypeId;

final class AddendumTypeDeleteUseCase
{
    private $addendumTypeContract;

    public function __construct(AddendumTypeContract $addendumTypeContract)
    {
        $this->addendumTypeContract = $addendumTypeContract;
    }

    public function __invoke(AddendumTypeId $addendumTypeId): ?AddendumType
    {
        return $this->addendumTypeContract->delete($addendumTypeId);
    }
}
