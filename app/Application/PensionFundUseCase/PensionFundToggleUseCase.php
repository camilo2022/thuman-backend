<?php

declare(strict_types=1);

namespace App\Application\PensionFundUseCase;

use App\Domain\PensionFund\Contracts\PensionFundContract;
use App\Domain\PensionFund\PensionFund;
use App\Domain\PensionFund\Values\PensionFundCountryId;
use App\Domain\PensionFund\Values\PensionFundDescription;
use App\Domain\PensionFund\Values\PensionFundId;
use App\Domain\PensionFund\Values\PensionFundItemId;
use App\Domain\PensionFund\Values\PensionFundName;
use App\Domain\PensionFund\Values\PensionFundSettings;
use App\Http\Requests\PensionFund\PensionFundToggleRequest;

final class PensionFundToggleUseCase
{
    private $pensionFundContract;

    public function __construct(PensionFundContract $pensionFundContract)
    {
        $this->pensionFundContract = $pensionFundContract;
    }

    public function __invoke(PensionFundToggleRequest $request, PensionFundId $pensionFundId): PensionFund
    {
        return $this->pensionFundContract->toggle(
            new PensionFund(
                new PensionFundId($pensionFundId->value()),
                new PensionFundItemId($request->integer('item_id')),
                new PensionFundName($request->input('name')),
                new PensionFundDescription($request->input('description')),
                new PensionFundSettings((object) []),
                new PensionFundCountryId($request->integer('country_id'))
            ),
            $pensionFundId
        );
    }
}
