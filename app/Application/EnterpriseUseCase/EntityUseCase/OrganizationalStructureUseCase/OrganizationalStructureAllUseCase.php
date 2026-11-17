<?php

declare(strict_types=1);

namespace App\Application\EnterpriseUseCase\EntityUseCase\OrganizationalStructureUseCase;

use App\Domain\OrganizationalStructure\Contracts\OrganizationalStructureContract;
use App\Http\Requests\Enterprise\Entity\OrganizationalStructure\OrganizationalStructureAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class OrganizationalStructureAllUseCase
{
    private $organizationalStructureContract;

    public function __construct(OrganizationalStructureContract $organizationalStructureContract)
    {
        $this->organizationalStructureContract = $organizationalStructureContract;
    }

    public function __invoke(OrganizationalStructureAllRequest $request): LengthAwarePaginator
    {
        return $this->organizationalStructureContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $request);
    }
}
