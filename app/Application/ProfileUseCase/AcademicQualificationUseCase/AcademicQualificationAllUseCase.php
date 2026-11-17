<?php

declare(strict_types=1);

namespace App\Application\ProfileUseCase\AcademicQualificationUseCase;

use App\Domain\Profile\AcademicQualification\Contracts\AcademicQualificationContract;
use App\Domain\Profile\Values\ProfileId;
use App\Http\Requests\Profile\AcademicQualification\AcademicQualificationAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class AcademicQualificationAllUseCase
{
    private $academicQualificationContract;

    public function __construct(AcademicQualificationContract $academicQualificationContract)
    {
        $this->academicQualificationContract = $academicQualificationContract;
    }

    public function __invoke(AcademicQualificationAllRequest $request, ProfileId $profileId): LengthAwarePaginator
    {
        return $this->academicQualificationContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $profileId);
    }
}
