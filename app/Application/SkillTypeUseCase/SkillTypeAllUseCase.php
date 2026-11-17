<?php

declare(strict_types=1);

namespace App\Application\SkillTypeUseCase;

use App\Domain\SkillType\Contracts\SkillTypeContract;
use App\Http\Requests\SkillType\SkillTypeAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class SkillTypeAllUseCase
{
    private $skillTypeContract;

    public function __construct(SkillTypeContract $skillTypeContract)
    {
        $this->skillTypeContract = $skillTypeContract;
    }

    public function __invoke(SkillTypeAllRequest $request): LengthAwarePaginator
    {
        return $this->skillTypeContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null);
    }
}
