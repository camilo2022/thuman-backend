<?php

declare(strict_types=1);

namespace App\Application\AcademicLevelUseCase;

use App\Domain\AcademicLevel\Contracts\AcademicLevelContract;
use App\Domain\AcademicLevel\AcademicLevel;
use App\Domain\AcademicLevel\Values\AcademicLevelDescription;
use App\Domain\AcademicLevel\Values\AcademicLevelId;
use App\Domain\AcademicLevel\Values\AcademicLevelItemId;
use App\Domain\AcademicLevel\Values\AcademicLevelName;
use App\Domain\AcademicLevel\Values\AcademicLevelSettings;
use App\Http\Requests\AcademicLevel\AcademicLevelUpdateRequest;

final class AcademicLevelUpdateUseCase
{
    private $academicLevelContract;

    public function __construct(AcademicLevelContract $academicLevelContract)
    {
        $this->academicLevelContract = $academicLevelContract;
    }

    public function __invoke(AcademicLevelUpdateRequest $request, AcademicLevelId $academicLevelId): AcademicLevel
    {
        return $this->academicLevelContract->update(
            new AcademicLevel(
                new AcademicLevelId($academicLevelId->value()),
                new AcademicLevelItemId($request->integer('item_id')),
                new AcademicLevelName($request->input('name')),
                new AcademicLevelDescription($request->input('description')),
                new AcademicLevelSettings((object) [])
            ),
            $academicLevelId
        );
    }
}
