<?php

declare(strict_types=1);

namespace App\Application\VacantUseCase\ApplicationUseCase;

use App\Domain\Vacant\Application\Contracts\ApplicationContract;
use App\Domain\Vacant\Values\VacantId;
use App\Http\Requests\Vacant\Application\ApplicationAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class ApplicationAllUseCase
{
    private $applicationContract;

    public function __construct(ApplicationContract $applicationContract)
    {
        $this->applicationContract = $applicationContract;
    }

    public function __invoke(ApplicationAllRequest $request, VacantId $vacantId): LengthAwarePaginator
    {
        return $this->applicationContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $request, $vacantId);
    }
}
