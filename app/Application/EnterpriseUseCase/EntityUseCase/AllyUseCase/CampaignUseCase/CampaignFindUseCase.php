<?php

declare(strict_types=1);

namespace App\Application\EnterpriseUseCase\EntityUseCase\AllyUseCase\CampaignUseCase;

use App\Domain\Enterprise\Entity\Ally\Campaign\Campaign;
use App\Domain\Enterprise\Entity\Ally\Campaign\Contracts\CampaignContract;
use App\Domain\Enterprise\Entity\Ally\Campaign\Values\CampaignId;

final class CampaignFindUseCase
{
    private $campaignContract;

    public function __construct(CampaignContract $campaignContract)
    {
        $this->campaignContract = $campaignContract;
    }

    public function __invoke(CampaignId $campaignId): ?Campaign
    {
        return $this->campaignContract->find($campaignId);
    }
}
