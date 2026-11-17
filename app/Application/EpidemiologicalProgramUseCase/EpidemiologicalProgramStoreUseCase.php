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
use App\Http\Requests\EpidemiologicalProgram\EpidemiologicalProgramStoreRequest;

final class EpidemiologicalProgramStoreUseCase
{
    private $epidemiologicalProgramContract;

    public function __construct(EpidemiologicalProgramContract $epidemiologicalProgramContract)
    {
        $this->epidemiologicalProgramContract = $epidemiologicalProgramContract;
    }

    public function __invoke(EpidemiologicalProgramStoreRequest $request): EpidemiologicalProgram
    {
        return $this->epidemiologicalProgramContract->store(
            new EpidemiologicalProgram(
                new EpidemiologicalProgramId(null),
                new EpidemiologicalProgramItemId($request->integer('item_id')),
                new EpidemiologicalProgramName($request->input('name')),
                new EpidemiologicalProgramDescription($request->input('description')),
                new EpidemiologicalProgramSettings((object) [])
            )
        );
    }
}
