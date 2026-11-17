<?php

declare(strict_types=1);

namespace App\Application\SexUseCase;

use App\Domain\Sex\Contracts\SexContract;
use App\Http\Requests\Sex\SexAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class SexAllUseCase
{
    private $sexContract;

    public function __construct(SexContract $sexContract)
    {
        $this->sexContract = $sexContract;
    }

    public function __invoke(SexAllRequest $request): LengthAwarePaginator
    {
        return $this->sexContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null);
    }
}
