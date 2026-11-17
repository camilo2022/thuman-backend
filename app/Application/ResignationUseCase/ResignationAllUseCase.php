<?php

declare(strict_types=1);

namespace App\Application\ResignationUseCase;

use App\Domain\Resignation\Contracts\ResignationContract;
use App\Http\Requests\Resignation\ResignationAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class ResignationAllUseCase
{
    private $resignationContract;

    public function __construct(ResignationContract $resignationContract)
    {
        $this->resignationContract = $resignationContract;
    }

    public function __invoke(ResignationAllRequest $request): LengthAwarePaginator
    {
        return $this->resignationContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $request);
    }
}
