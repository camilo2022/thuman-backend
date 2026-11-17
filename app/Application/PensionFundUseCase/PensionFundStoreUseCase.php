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
use App\Http\Requests\PensionFund\PensionFundStoreRequest;

final class PensionFundStoreUseCase
{
    private $pensionFundContract;

    public function __construct(PensionFundContract $pensionFundContract)
    {
        $this->pensionFundContract = $pensionFundContract;
    }

    public function __invoke(PensionFundStoreRequest $request): PensionFund
    {
        return $this->pensionFundContract->store(
            new PensionFund(
                new PensionFundId(null),
                new PensionFundItemId($request->integer('item_id')),
                new PensionFundName($request->input('name')),
                new PensionFundDescription($request->input('description')),
                new PensionFundSettings((object) [])
            )
        );
    }
}
