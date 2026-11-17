<?php

declare(strict_types=1);

namespace App\Application\PensionFundUseCase;

use App\Domain\PensionFund\Contracts\PensionFundContract;
use App\Domain\PensionFund\PensionFund;
use App\Domain\PensionFund\Values\PensionFundDescription;
use App\Domain\PensionFund\Values\PensionFundId;
use App\Domain\PensionFund\Values\PensionFundItemId;
use App\Domain\PensionFund\Values\PensionFundName;
use App\Domain\PensionFund\Values\PensionFundSettings;
use App\Http\Requests\PensionFund\PensionFundUpdateRequest;

final class PensionFundUpdateUseCase
{
    private $pensionFundContract;

    public function __construct(PensionFundContract $pensionFundContract)
    {
        $this->pensionFundContract = $pensionFundContract;
    }

    public function __invoke(PensionFundUpdateRequest $request, PensionFundId $pensionFundId): PensionFund
    {
        return $this->pensionFundContract->update(
            new PensionFund(
                new PensionFundId($pensionFundId->value()),
                new PensionFundItemId($request->integer('item_id')),
                new PensionFundName($request->input('name')),
                new PensionFundDescription($request->input('description')),
                new PensionFundSettings((object) [])
            ),
            $pensionFundId
        );
    }
}
