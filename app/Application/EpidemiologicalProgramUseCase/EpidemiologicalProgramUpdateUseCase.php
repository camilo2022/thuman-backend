<?php

declare(strict_types=1);

namespace App\Application\EpidemiologicalProgramUseCase;

use App\Domain\EpidemiologicalProgram\Contracts\EpidemiologicalProgramContract;
use App\Domain\EpidemiologicalProgram\EpidemiologicalProgram;
use App\Domain\EpidemiologicalProgram\Values\EpidemiologicalProgramDescription;
use App\Domain\EpidemiologicalProgram\Values\EpidemiologicalProgramId;
use App\Domain\EpidemiologicalProgram\Values\EpidemiologicalProgramItemId;
use App\Domain\EpidemiologicalProgram\Values\EpidemiologicalProgramName;
use App\Domain\EpidemiologicalProgram\Values\EpidemiologicalProgramSettings;
use App\Http\Requests\EpidemiologicalProgram\EpidemiologicalProgramUpdateRequest;

final class EpidemiologicalProgramUpdateUseCase
{
    private $epidemiologicalProgramContract;

    public function __construct(EpidemiologicalProgramContract $epidemiologicalProgramContract)
    {
        $this->epidemiologicalProgramContract = $epidemiologicalProgramContract;
    }

    public function __invoke(EpidemiologicalProgramUpdateRequest $request, EpidemiologicalProgramId $epidemiologicalProgramId): EpidemiologicalProgram
    {
        return $this->epidemiologicalProgramContract->update(
            new EpidemiologicalProgram(
                new EpidemiologicalProgramId($epidemiologicalProgramId->value()),
                new EpidemiologicalProgramItemId($request->integer('item_id')),
                new EpidemiologicalProgramName($request->input('name')),
                new EpidemiologicalProgramDescription($request->input('description')),
                new EpidemiologicalProgramSettings((object) $request->input('settings'))
            ),
            $epidemiologicalProgramId
        );
    }
}
