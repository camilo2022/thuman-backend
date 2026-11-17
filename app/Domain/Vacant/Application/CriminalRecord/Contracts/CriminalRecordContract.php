<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\CriminalRecord\Contracts;

use App\Domain\Vacant\Application\CriminalRecord\CriminalRecord;
use App\Domain\Vacant\Application\CriminalRecord\File;
use App\Domain\Vacant\Application\CriminalRecord\Values\CriminalRecordId;
use App\Http\Requests\Vacant\Application\CriminalRecord\CriminalRecordFindRequest;

interface CriminalRecordContract
{
    public function find(CriminalRecordFindRequest $request, CriminalRecordId $criminalRecordId): ?CriminalRecord;

    public function store(CriminalRecord $criminalRecord): CriminalRecord;

    public function update(CriminalRecord $criminalRecord, CriminalRecordId $criminalRecordId): CriminalRecord;

    public function file(File $file): File;
}
