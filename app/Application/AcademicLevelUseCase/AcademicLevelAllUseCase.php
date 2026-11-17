<?php

declare(strict_types=1);

namespace App\Application\AcademicLevelUseCase;

use App\Domain\AcademicLevel\Contracts\AcademicLevelContract;
use App\Http\Requests\AcademicLevel\AcademicLevelAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class AcademicLevelAllUseCase
{
    private $academicLevelContract;

    public function __construct(AcademicLevelContract $academicLevelContract)
    {
        $this->academicLevelContract = $academicLevelContract;
    }

    public function __invoke(AcademicLevelAllRequest $request): LengthAwarePaginator
    {
        return $this->academicLevelContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $request);
    }
}
