<?php

declare(strict_types=1);

namespace App\Application\EnterpriseUseCase\EntityUseCase\AllyUseCase;

use App\Domain\Enterprise\Entity\Ally\Contracts\AllyContract;
use App\Domain\Enterprise\Entity\Values\EntityId;
use App\Http\Requests\Enterprise\Entity\Ally\AllyAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class AllyAllUseCase
{
    private $allyContract;

    public function __construct(AllyContract $allyContract)
    {
        $this->allyContract = $allyContract;
    }

    public function __invoke(AllyAllRequest $request, EntityId $entityId): LengthAwarePaginator
    {
        return $this->allyContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $entityId, $request);
    }
}
