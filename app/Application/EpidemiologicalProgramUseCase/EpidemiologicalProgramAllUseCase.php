<?php

declare(strict_types=1);

namespace App\Application\EpidemiologicalProgramUseCase;

use App\Domain\EpidemiologicalProgram\Contracts\EpidemiologicalProgramContract;
use App\Http\Requests\EpidemiologicalProgram\EpidemiologicalProgramAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class EpidemiologicalProgramAllUseCase
{
    private $epidemiologicalProgramContract;

    public function __construct(EpidemiologicalProgramContract $epidemiologicalProgramContract)
    {
        $this->epidemiologicalProgramContract = $epidemiologicalProgramContract;
    }

    public function __invoke(EpidemiologicalProgramAllRequest $request): LengthAwarePaginator
    {
        return $this->epidemiologicalProgramContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $request);
    }
}
