<?php

declare(strict_types=1);

namespace App\Application\MaritalStatusUseCase;

use App\Domain\MaritalStatus\Contracts\MaritalStatusContract;
use App\Domain\MaritalStatus\MaritalStatus;
use App\Domain\MaritalStatus\Values\MaritalStatusDescription;
use App\Domain\MaritalStatus\Values\MaritalStatusId;
use App\Domain\MaritalStatus\Values\MaritalStatusItemId;
use App\Domain\MaritalStatus\Values\MaritalStatusName;
use App\Domain\MaritalStatus\Values\MaritalStatusSettings;
use App\Http\Requests\MaritalStatus\MaritalStatusUpdateRequest;

final class MaritalStatusUpdateUseCase
{
    private $maritalStatusContract;

    public function __construct(MaritalStatusContract $maritalStatusContract)
    {
        $this->maritalStatusContract = $maritalStatusContract;
    }

    public function __invoke(MaritalStatusUpdateRequest $request, MaritalStatusId $maritalStatusId): MaritalStatus
    {
        return $this->maritalStatusContract->update(
            new MaritalStatus(
                new MaritalStatusId($maritalStatusId->value()),
                new MaritalStatusItemId($request->integer('item_id')),
                new MaritalStatusName($request->input('name')),
                new MaritalStatusDescription($request->input('description')),
                new MaritalStatusSettings((object) [])
            ),
            $maritalStatusId
        );
    }
}
