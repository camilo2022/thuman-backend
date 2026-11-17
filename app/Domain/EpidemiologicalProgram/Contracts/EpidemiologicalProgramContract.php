<?php

declare(strict_types=1);

namespace App\Domain\EpidemiologicalProgram\Contracts;

use App\Domain\EpidemiologicalProgram\EpidemiologicalProgram;
use App\Domain\EpidemiologicalProgram\Values\EpidemiologicalProgramId;
use App\Http\Requests\EpidemiologicalProgram\EpidemiologicalProgramAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface EpidemiologicalProgramContract
{
    public function all(string|null $search, int|null $perPage, EpidemiologicalProgramAllRequest $request): LengthAwarePaginator;

    public function find(EpidemiologicalProgramId $epidemiologicalProgramId): ?EpidemiologicalProgram;

    public function store(EpidemiologicalProgram $epidemiologicalProgram): EpidemiologicalProgram;

    public function update(EpidemiologicalProgram $epidemiologicalProgram, EpidemiologicalProgramId $epidemiologicalProgramId): EpidemiologicalProgram;

    public function delete(EpidemiologicalProgramId $epidemiologicalProgramId): EpidemiologicalProgram;

    public function restore(EpidemiologicalProgramId $epidemiologicalProgramId): EpidemiologicalProgram;
}
