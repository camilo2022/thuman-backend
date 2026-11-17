<?php

declare(strict_types=1);

namespace App\Application\PositionUseCase\ResponsabilityUseCase;

use App\Domain\Position\Responsability\Contracts\ResponsabilityContract;
use App\Domain\Position\Values\PositionId;
use App\Http\Requests\Position\Responsability\ResponsabilityAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class ResponsabilityAllUseCase
{
    private $responsabilityContract;

    public function __construct(ResponsabilityContract $responsabilityContract)
    {
        $this->responsabilityContract = $responsabilityContract;
    }

    public function __invoke(ResponsabilityAllRequest $request, PositionId $positionId): LengthAwarePaginator
    {
        return $this->responsabilityContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $positionId);
    }
}
