<?php

declare(strict_types=1);

namespace App\Application\ResignationTypeUseCase;

use App\Domain\ResignationType\Contracts\ResignationTypeContract;
use App\Http\Requests\ResignationType\ResignationTypeAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class ResignationTypeAllUseCase
{
    private $resignationTypeContract;

    public function __construct(ResignationTypeContract $resignationTypeContract)
    {
        $this->resignationTypeContract = $resignationTypeContract;
    }

    public function __invoke(ResignationTypeAllRequest $request): LengthAwarePaginator
    {
        return $this->resignationTypeContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $request);
    }
}
