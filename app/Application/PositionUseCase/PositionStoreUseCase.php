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
use App\Http\Requests\Position\PositionStoreRequest;

final class PositionStoreUseCase
{
    private $positionContract;

    public function __construct(PositionContract $positionContract)
    {
        $this->positionContract = $positionContract;
    }

    public function __invoke(PositionStoreRequest $request): Position
    {
        return $this->positionContract->store(
            new Position(
                new PositionId(null),
                new PositionAreaId($request->integer('area_id')),
                new PositionName($request->input('name')),
                new PositionProfileCategoryId($request->integer('profile_category_id')),
                new PositionDescription($request->input('description')),
                new PositionSettings($request->input('settings'))
            )
        );
    }
}
