<?php

declare(strict_types=1);

namespace App\Application\ContractUseCase\PolicyUseCase;

use App\Domain\Contract\Policy\Contracts\PolicyContract;
use App\Domain\Contract\Policy\Policy;
use App\Domain\Contract\Policy\Values\PolicyCode;
use App\Domain\Contract\Policy\Values\PolicyEndDate;
use App\Domain\Contract\Policy\Values\PolicyId;
use App\Domain\Contract\Policy\Values\PolicyInsuredValue;
use App\Domain\Contract\Policy\Values\PolicyModelId;
use App\Domain\Contract\Policy\Values\PolicyModelType;
use App\Domain\Contract\Policy\Values\PolicyPolicyTypeId;
use App\Domain\Contract\Policy\Values\PolicyRiskManagerId;
use App\Domain\Contract\Policy\Values\PolicyStartDate;
use App\Domain\Contract\Policy\Values\PolicyStatus;
use App\Domain\Contract\Policy\Values\PolicyUuid;
use App\Domain\Contract\Policy\Values\PolicyValue;
use App\Models\Policy as PolicyModel;
use App\Http\Requests\Contract\Policy\PolicyActivedRequest;

final class PolicyActivedUseCase
{
    private $policyContract;

    public function __construct(PolicyContract $policyContract)
    {
        $this->policyContract = $policyContract;
    }

    public function __invoke(PolicyActivedRequest $request, PolicyId $policyId): Policy
    {
        return $this->policyContract->update(
            new Policy(
                new PolicyId($policyId->value()),
                new PolicyUuid(null),
                new PolicyModelId($request->integer('model_id')),
                new PolicyModelType($request->input('model_type')),
                new PolicyRiskManagerId($request->integer('risk_manager_id')),
                new PolicyPolicyTypeId($request->integer('policy_type_id')),
                new PolicyCode($request->input('code')),
                new PolicyValue((float) $request->get('value')),
                new PolicyInsuredValue((float) $request->get('insured_value')),
                new PolicyStartDate($request->input('start_date')),
                new PolicyEndDate($request->input('end_date')),
                new PolicyStatus(PolicyModel::REVIEW)
            ),
            $policyId
        );
    }
}
