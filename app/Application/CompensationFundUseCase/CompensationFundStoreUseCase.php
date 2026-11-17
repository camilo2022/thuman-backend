<?php

declare(strict_types=1);

namespace App\Application\CompensationFundUseCase;

use App\Domain\CompensationFund\Contracts\CompensationFundContract;
use App\Domain\CompensationFund\CompensationFund;
use App\Domain\CompensationFund\Values\CompensationFundDescription;
use App\Domain\CompensationFund\Values\CompensationFundId;
use App\Domain\CompensationFund\Values\CompensationFundItemId;
use App\Domain\CompensationFund\Values\CompensationFundName;
use App\Domain\CompensationFund\Values\CompensationFundSettings;
use App\Http\Requests\CompensationFund\CompensationFundStoreRequest;

final class CompensationFundStoreUseCase
{
    private $compensationFundContract;

    public function __construct(CompensationFundContract $compensationFundContract)
    {
        $this->compensationFundContract = $compensationFundContract;
    }

    public function __invoke(CompensationFundStoreRequest $request): CompensationFund
    {
        return $this->compensationFundContract->store(
            new CompensationFund(
                new CompensationFundId(null),
                new CompensationFundItemId($request->integer('item_id')),
                new CompensationFundName($request->input('name')),
                new CompensationFundDescription($request->input('description')),
                new CompensationFundSettings((object) [])
            )
        );
    }
}
