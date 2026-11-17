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
use App\Http\Requests\CompensationFund\CompensationFundUpdateRequest;

final class CompensationFundUpdateUseCase
{
    private $compensationFundContract;

    public function __construct(CompensationFundContract $compensationFundContract)
    {
        $this->compensationFundContract = $compensationFundContract;
    }

    public function __invoke(CompensationFundUpdateRequest $request, CompensationFundId $compensationFundId): CompensationFund
    {
        return $this->compensationFundContract->update(
            new CompensationFund(
                new CompensationFundId($compensationFundId->value()),
                new CompensationFundItemId($request->integer('item_id')),
                new CompensationFundName($request->input('name')),
                new CompensationFundDescription($request->input('description')),
                new CompensationFundSettings((object) [])
            ),
            $compensationFundId
        );
    }
}
