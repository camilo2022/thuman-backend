<?php

declare(strict_types=1);

namespace App\Application\PolicyTypeUseCase;

use App\Domain\PolicyType\Contracts\PolicyTypeContract;
use App\Domain\PolicyType\PolicyType;
use App\Domain\PolicyType\Values\PolicyTypeDescription;
use App\Domain\PolicyType\Values\PolicyTypeId;
use App\Domain\PolicyType\Values\PolicyTypeItemId;
use App\Domain\PolicyType\Values\PolicyTypeName;
use App\Domain\PolicyType\Values\PolicyTypeSettings;
use App\Http\Requests\PolicyType\PolicyTypeUpdateRequest;

final class PolicyTypeUpdateUseCase
{
    private $policyTypeContract;

    public function __construct(PolicyTypeContract $policyTypeContract)
    {
        $this->policyTypeContract = $policyTypeContract;
    }

    public function __invoke(PolicyTypeUpdateRequest $request, PolicyTypeId $policyTypeId): PolicyType
    {
        return $this->policyTypeContract->update(
            new PolicyType(
                new PolicyTypeId($policyTypeId->value()),
                new PolicyTypeItemId($request->integer('item_id')),
                new PolicyTypeName($request->input('name')),
                new PolicyTypeDescription($request->input('description')),
                new PolicyTypeSettings((object) [])
            ),
            $policyTypeId
        );
    }
}
