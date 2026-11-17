<?php

declare(strict_types=1);

namespace App\Domain\Enterprise\Entity\Ally\Campaign\Contracts;

use App\Domain\Enterprise\Entity\Ally\Campaign\Campaign;
use App\Domain\Enterprise\Entity\Ally\Campaign\Values\CampaignId;
use App\Domain\Enterprise\Entity\Ally\Values\AllyId;
use App\Http\Requests\Enterprise\Entity\Ally\Campaign\CampaignAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface CampaignContract
{
    public function all(string|null $search, int|null $perPage, AllyId $allyId, CampaignAllRequest $request): LengthAwarePaginator;

    public function find(CampaignId $campaignId): ?Campaign;

    public function store(Campaign $campaign): Campaign;

    public function update(Campaign $campaign, CampaignId $campaignId): Campaign;

    public function delete(CampaignId $campaignId): Campaign;

    public function restore(CampaignId $campaignId): Campaign;
}
