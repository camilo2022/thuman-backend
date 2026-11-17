<?php

declare(strict_types=1);

namespace App\Application\InductionUseCase;

use App\Domain\Induction\Contracts\InductionContract;
use App\Domain\Induction\Induction;
use App\Domain\Induction\Values\InductionAssignableId;
use App\Domain\Induction\Values\InductionAssignableType;
use App\Domain\Induction\Values\InductionDescription;
use App\Domain\Induction\Values\InductionId;
use App\Domain\Induction\Values\InductionModelId;
use App\Domain\Induction\Values\InductionModelType;
use App\Domain\Induction\Values\InductionName;
use App\Http\Requests\Induction\InductionUpdateRequest;

final class InductionUpdateUseCase
{
    private $inductionContract;

    public function __construct(InductionContract $inductionContract)
    {
        $this->inductionContract = $inductionContract;
    }

    public function __invoke(InductionUpdateRequest $request, InductionId $inductionId): Induction
    {
        return $this->inductionContract->update(
            new Induction(
                new InductionId($inductionId->value()),
                new InductionName($request->input('name')),
                new InductionDescription($request->input('description')),
                new InductionAssignableId($request->input('assignable_id')),
                new InductionAssignableType($request->input('assignable_type')),
                new InductionModelId($request->input('model_id')),
                new InductionModelType($request->input('model_type'))
            ),
            $inductionId
        );
    }
}
