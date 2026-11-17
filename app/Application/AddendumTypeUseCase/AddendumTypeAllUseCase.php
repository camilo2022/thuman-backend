<?php

declare(strict_types=1);

namespace App\Application\AddendumTypeUseCase;

use App\Domain\AddendumType\Contracts\AddendumTypeContract;
use App\Http\Requests\AddendumType\AddendumTypeAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class AddendumTypeAllUseCase
{
    private $addendumTypeContract;

    public function __construct(AddendumTypeContract $addendumTypeContract)
    {
        $this->addendumTypeContract = $addendumTypeContract;
    }

    public function __invoke(AddendumTypeAllRequest $request): LengthAwarePaginator
    {
        return $this->addendumTypeContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $request);
    }
}
