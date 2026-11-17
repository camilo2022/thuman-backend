<?php

declare(strict_types=1);

namespace App\Application\EnterpriseUseCase\EntityUseCase\AllyUseCase\CampaignUseCase;

use App\Domain\Enterprise\Entity\Ally\Campaign\Contracts\CampaignContract;
use App\Domain\Enterprise\Entity\Ally\Values\AllyId;
use App\Http\Requests\Enterprise\Entity\Ally\Campaign\CampaignAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class CampaignAllUseCase
{
    private $campaignContract;

    public function __construct(CampaignContract $campaignContract)
    {
        $this->campaignContract = $campaignContract;
    }

    public function __invoke(CampaignAllRequest $request, AllyId $allyId): LengthAwarePaginator
    {
        return $this->campaignContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $allyId, $request);
    }
}
