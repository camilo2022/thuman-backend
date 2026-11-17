<?php

declare(strict_types=1);

namespace App\Application\EpidemiologicalProgramUseCase;

use App\Domain\EpidemiologicalProgram\EpidemiologicalProgram;
use App\Domain\EpidemiologicalProgram\Contracts\EpidemiologicalProgramContract;
use App\Domain\EpidemiologicalProgram\Values\EpidemiologicalProgramId;

final class EpidemiologicalProgramDeleteUseCase
{
    private $epidemiologicalProgramContract;

    public function __construct(EpidemiologicalProgramContract $epidemiologicalProgramContract)
    {
        $this->epidemiologicalProgramContract = $epidemiologicalProgramContract;
    }

    public function __invoke(EpidemiologicalProgramId $epidemiologicalProgramId): ?EpidemiologicalProgram
    {
        return $this->epidemiologicalProgramContract->delete($epidemiologicalProgramId);
    }
}
