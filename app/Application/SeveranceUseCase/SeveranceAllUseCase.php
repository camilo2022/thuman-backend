<?php

declare(strict_types=1);

namespace App\Application\SeveranceUseCase;

use App\Domain\Severance\Contracts\SeveranceContract;
use App\Http\Requests\Severance\SeveranceAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class SeveranceAllUseCase
{
    private $severanceContract;

    public function __construct(SeveranceContract $severanceContract)
    {
        $this->severanceContract = $severanceContract;
    }

    public function __invoke(SeveranceAllRequest $request): LengthAwarePaginator
    {
        return $this->severanceContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $request);
    }
}
