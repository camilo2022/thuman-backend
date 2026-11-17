<?php

declare(strict_types=1);

namespace App\Application\SexUseCase;

use App\Domain\Sex\Sex;
use App\Domain\Sex\Contracts\SexContract;
use App\Domain\Sex\Values\SexId;

final class SexFindUseCase
{
    private $sexContract;

    public function __construct(SexContract $sexContract)
    {
        $this->sexContract = $sexContract;
    }

    public function __invoke(SexId $sexId): ?Sex
    {
        return $this->sexContract->find($sexId);
    }
}
