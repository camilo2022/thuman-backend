<?php

declare(strict_types=1);

namespace App\Application\SkillUseCase;

use App\Domain\Skill\Contracts\SkillContract;
use App\Http\Requests\Skill\SkillAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class SkillAllUseCase
{
    private $skillContract;

    public function __construct(SkillContract $skillContract)
    {
        $this->skillContract = $skillContract;
    }

    public function __invoke(SkillAllRequest $request): LengthAwarePaginator
    {
        return $this->skillContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $request);
    }
}
