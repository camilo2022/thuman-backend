<?php

declare(strict_types=1);

namespace App\Application\RiskManagerUseCase;

use App\Domain\RiskManager\Contracts\RiskManagerContract;
use App\Domain\RiskManager\RiskManager;
use App\Domain\RiskManager\Values\RiskManagerDescription;
use App\Domain\RiskManager\Values\RiskManagerId;
use App\Domain\RiskManager\Values\RiskManagerItemId;
use App\Domain\RiskManager\Values\RiskManagerName;
use App\Domain\RiskManager\Values\RiskManagerSettings;
use App\Http\Requests\RiskManager\RiskManagerUpdateRequest;

final class RiskManagerUpdateUseCase
{
    private $riskManagerContract;

    public function __construct(RiskManagerContract $riskManagerContract)
    {
        $this->riskManagerContract = $riskManagerContract;
    }

    public function __invoke(RiskManagerUpdateRequest $request, RiskManagerId $riskManagerId): RiskManager
    {
        return $this->riskManagerContract->update(
            new RiskManager(
                new RiskManagerId($riskManagerId->value()),
                new RiskManagerItemId($request->integer('item_id')),
                new RiskManagerName($request->input('name')),
                new RiskManagerDescription($request->input('description')),
                new RiskManagerSettings((object) [])
            ),
            $riskManagerId
        );
    }
}
