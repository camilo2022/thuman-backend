<?php

declare(strict_types=1);

namespace App\Application\RiskManagerUseCase;

use App\Domain\RiskManager\Contracts\RiskManagerContract;
use App\Domain\RiskManager\RiskManager;
use App\Domain\RiskManager\Values\RiskManagerCountryId;
use App\Domain\RiskManager\Values\RiskManagerDescription;
use App\Domain\RiskManager\Values\RiskManagerId;
use App\Domain\RiskManager\Values\RiskManagerItemId;
use App\Domain\RiskManager\Values\RiskManagerName;
use App\Domain\RiskManager\Values\RiskManagerSettings;
use App\Http\Requests\RiskManager\RiskManagerToggleRequest;

final class RiskManagerToggleUseCase
{
    private $riskManagerContract;

    public function __construct(RiskManagerContract $riskManagerContract)
    {
        $this->riskManagerContract = $riskManagerContract;
    }

    public function __invoke(RiskManagerToggleRequest $request, RiskManagerId $riskManagerId): RiskManager
    {
        return $this->riskManagerContract->toggle(
            new RiskManager(
                new RiskManagerId($riskManagerId->value()),
                new RiskManagerItemId($request->integer('item_id')),
                new RiskManagerName($request->input('name')),
                new RiskManagerDescription($request->input('description')),
                new RiskManagerSettings((object) []),
                new RiskManagerCountryId($request->integer('country_id'))
            ),
            $riskManagerId
        );
    }
}
