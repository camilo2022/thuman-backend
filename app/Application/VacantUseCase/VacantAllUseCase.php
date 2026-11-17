<?php

declare(strict_types=1);

namespace App\Application\VacantUseCase;

use App\Domain\Vacant\Contracts\VacantContract;
use App\Http\Requests\Vacant\VacantAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class VacantAllUseCase
{
    private $vacantContract;

    public function __construct(VacantContract $vacantContract)
    {
        $this->vacantContract = $vacantContract;
    }

    public function __invoke(VacantAllRequest $request): LengthAwarePaginator
    {
        return $this->vacantContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $request);
    }
}
