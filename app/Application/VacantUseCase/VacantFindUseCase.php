<?php

declare(strict_types=1);

namespace App\Application\VacantUseCase;

use App\Domain\Vacant\Vacant;
use App\Domain\Vacant\Contracts\VacantContract;
use App\Domain\Vacant\Values\VacantId;
use App\Http\Requests\Vacant\VacantFindRequest;

final class VacantFindUseCase
{
    private $vacantContract;

    public function __construct(VacantContract $vacantContract)
    {
        $this->vacantContract = $vacantContract;
    }

    public function __invoke(VacantFindRequest $request, VacantId $vacantId): ?Vacant
    {
        return $this->vacantContract->find($request, $vacantId);
    }
}
