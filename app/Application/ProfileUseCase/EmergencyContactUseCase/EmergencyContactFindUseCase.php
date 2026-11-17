<?php

declare(strict_types=1);

namespace App\Application\ProfileUseCase\EmergencyContactUseCase;

use App\Domain\Profile\EmergencyContact\EmergencyContact;
use App\Domain\Profile\EmergencyContact\Contracts\EmergencyContactContract;
use App\Domain\Profile\EmergencyContact\Values\EmergencyContactId;
use App\Http\Requests\Profile\EmergencyContact\EmergencyContactFindRequest;

final class EmergencyContactFindUseCase
{
    private $emergencyContactContract;

    public function __construct(EmergencyContactContract $emergencyContactContract)
    {
        $this->emergencyContactContract = $emergencyContactContract;
    }

    public function __invoke(EmergencyContactFindRequest $request, EmergencyContactId $emergencyContactId): ?EmergencyContact
    {
        return $this->emergencyContactContract->find($request, $emergencyContactId);
    }
}
