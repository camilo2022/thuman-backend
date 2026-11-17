<?php

declare(strict_types=1);

namespace App\Application\VacantUseCase\ApplicationUseCase\CriminalRecordUseCase;

use App\Domain\Vacant\Application\CriminalRecord\CriminalRecord;
use App\Domain\Vacant\Application\CriminalRecord\Values\CriminalRecordId;
use App\Domain\Vacant\Application\CriminalRecord\Contracts\CriminalRecordContract;
use App\Http\Requests\Vacant\Application\CriminalRecord\CriminalRecordFindRequest;

final class CriminalRecordFindUseCase
{
    private $criminalRecordContract;

    public function __construct(CriminalRecordContract $criminalRecordContract)
    {
        $this->criminalRecordContract = $criminalRecordContract;
    }

    public function __invoke(CriminalRecordFindRequest $request, CriminalRecordId $criminalRecordId): ?CriminalRecord
    {
        return $this->criminalRecordContract->find($request, $criminalRecordId);
    }
}
