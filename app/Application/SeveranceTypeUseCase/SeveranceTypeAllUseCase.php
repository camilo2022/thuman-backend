<?php

declare(strict_types=1);

namespace App\Application\SeveranceTypeUseCase;

use App\Domain\SeveranceType\Contracts\SeveranceTypeContract;
use App\Http\Requests\SeveranceType\SeveranceTypeAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class SeveranceTypeAllUseCase
{
    private $severanceTypeContract;

    public function __construct(SeveranceTypeContract $severanceTypeContract)
    {
        $this->severanceTypeContract = $severanceTypeContract;
    }

    public function __invoke(SeveranceTypeAllRequest $request): LengthAwarePaginator
    {
        return $this->severanceTypeContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $request);
    }
}
