<?php

declare(strict_types=1);

namespace App\Domain\Contract\Policy\Values;

final class PolicyRelationships
{
    private ?object $model;
    private ?object $risk_manager;
    private ?object $policy_type;

    public function __construct(
        ?object $model = null,
        ?object $risk_manager = null,
        ?object $policy_type = null
    ) {
        $this->model = $model;
        $this->risk_manager = $risk_manager;
        $this->policy_type = $policy_type;
    }

    public function model(): ?object
    {
        return $this->model;
    }

    public function riskManager(): ?object
    {
        return $this->risk_manager;
    }

    public function policyType(): ?object
    {
        return $this->policy_type;
    }

    public function toArray(): array
    {
        return [
            'model' => $this->model,
            'risk_manager' => $this->risk_manager,
            'policy_type' => $this->policy_type
        ];
    }
}
