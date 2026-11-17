<?php

declare(strict_types=1);

namespace App\Application\PositionUseCase\RequiredExperienceUseCase;

use App\Domain\Position\RequiredExperience\Contracts\RequiredExperienceContract;
use App\Domain\Position\Values\PositionId;
use App\Http\Requests\Position\RequiredExperience\RequiredExperienceAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class RequiredExperienceAllUseCase
{
    private $requiredExperienceContract;

    public function __construct(RequiredExperienceContract $requiredExperienceContract)
    {
        $this->requiredExperienceContract = $requiredExperienceContract;
    }

    public function __invoke(RequiredExperienceAllRequest $request, PositionId $positionId): LengthAwarePaginator
    {
        return $this->requiredExperienceContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $positionId);
    }
}
