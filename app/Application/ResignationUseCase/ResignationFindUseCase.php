<?php

declare(strict_types=1);

namespace App\Application\ResignationUseCase;

use App\Domain\Resignation\Resignation;
use App\Domain\Resignation\Contracts\ResignationContract;
use App\Domain\Resignation\Values\ResignationId;

final class ResignationFindUseCase
{
    private $resignationContract;

    public function __construct(ResignationContract $resignationContract)
    {
        $this->resignationContract = $resignationContract;
    }

    public function __invoke(ResignationId $resignationId): ?Resignation
    {
        return $this->resignationContract->find($resignationId);
    }
}
