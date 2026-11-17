<?php

declare(strict_types=1);

namespace App\Application\SizeUseCase;

use App\Domain\Size\Contracts\SizeContract;
use App\Http\Requests\Size\SizeAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class SizeAllUseCase
{
    private $sizeContract;

    public function __construct(SizeContract $sizeContract)
    {
        $this->sizeContract = $sizeContract;
    }

    public function __invoke(SizeAllRequest $request): LengthAwarePaginator
    {
        return $this->sizeContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null);
    }
}
