<?php

declare(strict_types=1);

namespace App\Application\GenderUseCase;

use App\Domain\Gender\Contracts\GenderContract;
use App\Http\Requests\Gender\GenderAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class GenderAllUseCase
{
    private $genderContract;

    public function __construct(GenderContract $genderContract)
    {
        $this->genderContract = $genderContract;
    }

    public function __invoke(GenderAllRequest $request): LengthAwarePaginator
    {
        return $this->genderContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null);
    }
}
