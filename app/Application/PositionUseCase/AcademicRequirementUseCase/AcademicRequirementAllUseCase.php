<?php

declare(strict_types=1);

namespace App\Application\PositionUseCase\AcademicRequirementUseCase;

use App\Domain\Position\AcademicRequirement\Contracts\AcademicRequirementContract;
use App\Domain\Position\Values\PositionId;
use App\Http\Requests\Position\AcademicRequirement\AcademicRequirementAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class AcademicRequirementAllUseCase
{
    private $academicRequirementContract;

    public function __construct(AcademicRequirementContract $academicRequirementContract)
    {
        $this->academicRequirementContract = $academicRequirementContract;
    }

    public function __invoke(AcademicRequirementAllRequest $request, PositionId $positionId): LengthAwarePaginator
    {
        return $this->academicRequirementContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $positionId);
    }
}
