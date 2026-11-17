<?php

declare(strict_types=1);

namespace App\Application\EnterpriseUseCase\EntityUseCase\AllyUseCase\CampaignUseCase;

use App\Domain\Enterprise\Entity\Ally\Campaign\Contracts\CampaignContract;
use App\Domain\Enterprise\Entity\Ally\Campaign\Campaign;
use App\Domain\Enterprise\Entity\Ally\Campaign\Values\CampaignDescription;
use App\Domain\Enterprise\Entity\Ally\Campaign\Values\CampaignAllyId;
use App\Domain\Enterprise\Entity\Ally\Campaign\Values\CampaignId;
use App\Domain\Enterprise\Entity\Ally\Campaign\Values\CampaignName;
use App\Http\Requests\Enterprise\Entity\Ally\Campaign\CampaignStoreRequest;

final class CampaignStoreUseCase
{
    private $campaignContract;

    public function __construct(CampaignContract $campaignContract)
    {
        $this->campaignContract = $campaignContract;
    }

    public function __invoke(CampaignStoreRequest $request): Campaign
    {
        return $this->campaignContract->store(
            new Campaign(
                new CampaignId(null),
                new CampaignAllyId($request->input('ally_id')),
                new CampaignName($request->input('name')),
                new CampaignDescription($request->input('description')),
            )
        );
    }
}
