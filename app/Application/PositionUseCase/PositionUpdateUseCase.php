<?php

declare(strict_types=1);

namespace App\Application\PositionUseCase;

use App\Domain\Position\Contracts\PositionContract;
use App\Domain\Position\Position;
use App\Domain\Position\Values\PositionAreaId;
use App\Domain\Position\Values\PositionDescription;
use App\Domain\Position\Values\PositionId;
use App\Domain\Position\Values\PositionName;
use App\Domain\Position\Values\PositionProfileCategoryId;
use App\Domain\Position\Values\PositionSettings;
use App\Http\Requests\Position\PositionUpdateRequest;

final class PositionUpdateUseCase
{
    private $positionContract;

    public function __construct(PositionContract $positionContract)
    {
        $this->positionContract = $positionContract;
    }

    public function __invoke(PositionUpdateRequest $request, PositionId $positionId): Position
    {
        return $this->positionContract->update(
            new Position(
                new PositionId($positionId->value()),
                new PositionAreaId($request->integer('area_id')),
                new PositionName($request->input('name')),
                new PositionProfileCategoryId($request->integer('profile_category_id')),
                new PositionDescription($request->input('description')),
                new PositionSettings($request->input('settings'))
            ),
            $positionId
        );
    }
}
