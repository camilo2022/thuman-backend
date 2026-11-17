<?php

declare(strict_types=1);

namespace App\Application\TerminationUseCase;

use App\Domain\Termination\Contracts\TerminationContract;
use App\Domain\Termination\Termination;
use App\Domain\Termination\Values\TerminationDate;
use App\Domain\Termination\Values\TerminationDecision;
use App\Domain\Termination\Values\TerminationId;
use App\Domain\Termination\Values\TerminationModelId;
use App\Domain\Termination\Values\TerminationModelType;
use App\Domain\Termination\Values\TerminationPersonId;
use App\Domain\Termination\Values\TerminationReason;
use App\Domain\Termination\Values\TerminationStatus;
use App\Models\Termination as TerminationModel;
use App\Http\Requests\Termination\TerminationApprovedRequest;

final class TerminationApprovedUseCase
{
    private $terminationContract;

    public function __construct(TerminationContract $terminationContract)
    {
        $this->terminationContract = $terminationContract;
    }

    public function __invoke(TerminationApprovedRequest $request, TerminationId $terminationId): Termination
    {
        return $this->terminationContract->update(
            new Termination(
                new TerminationId($terminationId->value()),
                new TerminationPersonId($request->integer('person_id')),
                new TerminationModelId($request->integer('model_id')),
                new TerminationModelType($request->input('model_type')),
                new TerminationReason($request->input('reason')),
                new TerminationDecision($request->input('decision')),
                new TerminationDate($request->input('date')),
                new TerminationStatus(TerminationModel::APPROVED)
            ),
            $terminationId
        );
    }
}
