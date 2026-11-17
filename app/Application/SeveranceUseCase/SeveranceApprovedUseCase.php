<?php

declare(strict_types=1);

namespace App\Application\SeveranceUseCase;

use App\Domain\Severance\Contracts\SeveranceContract;
use App\Domain\Severance\Severance;
use App\Domain\Severance\Values\SeveranceSeveranceTypeId;
use App\Domain\Severance\Values\SeveranceId;
use App\Domain\Severance\Values\SeverancePersonId;
use App\Domain\Severance\Values\SeveranceReason;
use App\Domain\Severance\Values\SeveranceStatus;
use App\Models\Severance as SeveranceModel;
use App\Http\Requests\Severance\SeveranceApprovedRequest;

final class SeveranceApprovedUseCase
{
    private $severanceContract;

    public function __construct(SeveranceContract $severanceContract)
    {
        $this->severanceContract = $severanceContract;
    }

    public function __invoke(SeveranceApprovedRequest $request, SeveranceId $severanceId): Severance
    {
        return $this->severanceContract->update(
            new Severance(
                new SeveranceId($severanceId->value()),
                new SeverancePersonId($request->integer('person_id')),
                new SeveranceSeveranceTypeId($request->integer('severance_type_id')),
                new SeveranceReason($request->input('reason')),
                new SeveranceStatus(SeveranceModel::APPROVED)
            ),
            $severanceId
        );
    }
}
