<?php

declare(strict_types=1);

namespace App\Application\PositionUseCase\ResponsabilityUseCase;

use App\Domain\Position\Responsability\Contracts\ResponsabilityContract;
use App\Domain\Position\Responsability\Responsability;
use App\Domain\Position\Responsability\Values\ResponsabilityName;
use App\Domain\Position\Responsability\Values\ResponsabilityId;
use App\Domain\Position\Responsability\Values\ResponsabilityDescription;
use App\Domain\Position\Responsability\Values\ResponsabilityPositionId;
use App\Http\Requests\Position\Responsability\ResponsabilityUpdateRequest;

final class ResponsabilityUpdateUseCase
{
    private $responsabilityContract;

    public function __construct(ResponsabilityContract $responsabilityContract)
    {
        $this->responsabilityContract = $responsabilityContract;
    }

    public function __invoke(ResponsabilityUpdateRequest $request, ResponsabilityId $responsabilityId): Responsability
    {
        return $this->responsabilityContract->update(
            new Responsability(
                new ResponsabilityId($responsabilityId->value()),
                new ResponsabilityPositionId($request->integer('position_id')),
                new ResponsabilityName($request->input('name')),
                new ResponsabilityDescription($request->input('description'))
            ),
            $responsabilityId
        );
    }
}
