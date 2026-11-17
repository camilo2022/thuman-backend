<?php

declare(strict_types=1);

namespace App\Application\EnterpriseUseCase\EntityUseCase;

use App\Domain\Enterprise\Entity\Contracts\EntityContract;
use App\Domain\Enterprise\Values\EnterpriseId;
use App\Http\Requests\Enterprise\Entity\EntityAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class EntityAllUseCase
{
    private $entityContract;

    public function __construct(EntityContract $entityContract)
    {
        $this->entityContract = $entityContract;
    }

    public function __invoke(EntityAllRequest $request, EnterpriseId $enterpriseId): LengthAwarePaginator
    {
        return $this->entityContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $enterpriseId, $request);
    }
}
