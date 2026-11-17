<?php

declare(strict_types=1);

namespace App\Application\EnterpriseUseCase\EntityUseCase\HeadquarterUseCase;

use App\Domain\Enterprise\Entity\Headquarter\Contracts\HeadquarterContract;
use App\Domain\Enterprise\Entity\Values\EntityId;
use App\Http\Requests\Enterprise\Entity\Headquarter\HeadquarterAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class HeadquarterAllUseCase
{
    private $headquarterContract;

    public function __construct(HeadquarterContract $headquarterContract)
    {
        $this->headquarterContract = $headquarterContract;
    }

    public function __invoke(HeadquarterAllRequest $request, EntityId $entityId): LengthAwarePaginator
    {
        return $this->headquarterContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $entityId, $request);
    }
}
