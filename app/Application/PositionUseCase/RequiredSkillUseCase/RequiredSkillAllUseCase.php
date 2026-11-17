<?php

declare(strict_types=1);

namespace App\Application\PositionUseCase\RequiredSkillUseCase;

use App\Domain\Position\RequiredSkill\Contracts\RequiredSkillContract;
use App\Domain\Position\Values\PositionId;
use App\Http\Requests\Position\RequiredSkill\RequiredSkillAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class RequiredSkillAllUseCase
{
    private $requiredSkillContract;

    public function __construct(RequiredSkillContract $requiredSkillContract)
    {
        $this->requiredSkillContract = $requiredSkillContract;
    }

    public function __invoke(RequiredSkillAllRequest $request, PositionId $positionId): LengthAwarePaginator
    {
        return $this->requiredSkillContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $positionId);
    }
}
