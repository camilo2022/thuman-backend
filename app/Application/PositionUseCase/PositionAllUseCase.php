<?php

declare(strict_types=1);

namespace App\Application\PositionUseCase;

use App\Domain\Position\Contracts\PositionContract;
use App\Http\Requests\Position\PositionAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class PositionAllUseCase
{
    private $positionContract;

    public function __construct(PositionContract $positionContract)
    {
        $this->positionContract = $positionContract;
    }

    public function __invoke(PositionAllRequest $request): LengthAwarePaginator
    {
        return $this->positionContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $request);
    }
}
