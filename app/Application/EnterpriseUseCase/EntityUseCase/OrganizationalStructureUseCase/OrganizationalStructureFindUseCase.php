<?php

declare(strict_types=1);

namespace App\Application\EnterpriseUseCase\EntityUseCase\OrganizationalStructureUseCase;

use App\Domain\OrganizationalStructure\OrganizationalStructure;
use App\Domain\OrganizationalStructure\Contracts\OrganizationalStructureContract;
use App\Domain\OrganizationalStructure\Values\OrganizationalStructureId;

final class OrganizationalStructureFindUseCase
{
    private $organizationalStructureContract;

    public function __construct(OrganizationalStructureContract $organizationalStructureContract)
    {
        $this->organizationalStructureContract = $organizationalStructureContract;
    }

    public function __invoke(OrganizationalStructureId $organizationalStructureId): ?OrganizationalStructure
    {
        return $this->organizationalStructureContract->find($organizationalStructureId);
    }
}
