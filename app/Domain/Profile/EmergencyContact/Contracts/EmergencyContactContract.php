<?php

declare(strict_types=1);

namespace App\Domain\Profile\EmergencyContact\Contracts;

use App\Domain\Profile\EmergencyContact\EmergencyContact;
use App\Domain\Profile\EmergencyContact\Values\EmergencyContactId;
use App\Http\Requests\Profile\EmergencyContact\EmergencyContactFindRequest;

interface EmergencyContactContract
{
    public function find(EmergencyContactFindRequest $request, EmergencyContactId $emergencyContactId): ?EmergencyContact;

    public function store(EmergencyContact $emergencyContact): EmergencyContact;

    public function update(EmergencyContact $emergencyContact, EmergencyContactId $emergencyContactId): EmergencyContact;
}
