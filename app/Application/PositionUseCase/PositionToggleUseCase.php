<?php

declare(strict_types=1);

namespace App\Application\PositionUseCase;

use App\Domain\Position\Contracts\PositionContract;
use App\Domain\Position\Position;
use App\Domain\Position\Values\PositionAreaId;
use App\Domain\Position\Values\PositionDescription;
use App\Domain\Position\Values\PositionId;
use App\Domain\Position\Values\PositionName;
use App\Domain\Position\Values\PositionPermissionId;
use App\Domain\Position\Values\PositionProfileCategoryId;
use App\Domain\Position\Values\PositionSettings;
use App\Http\Requests\Position\PositionToggleRequest;

final class PositionToggleUseCase
{
    private $positionContract;

    public function __construct(PositionContract $positionContract)
    {
        $this->positionContract = $positionContract;
    }

    public function __invoke(PositionToggleRequest $request, PositionId $positionId): Position
    {
        return $this->positionContract->toggle(
            new Position(
                new PositionId($positionId->value()),
                new PositionAreaId($request->integer('area_id')),
                new PositionName($request->input('name')),
                new PositionProfileCategoryId($request->integer('profile_category_id')),
                new PositionDescription($request->input('description')),
                new PositionSettings($request->input('settings')),
                new PositionPermissionId($request->integer('permission_id'))
            ),
            $positionId
        );
    }
}
