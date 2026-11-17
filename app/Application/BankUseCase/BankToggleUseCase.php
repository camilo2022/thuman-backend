<?php

declare(strict_types=1);

namespace App\Application\BankUseCase;

use App\Domain\Bank\Contracts\BankContract;
use App\Domain\Bank\Bank;
use App\Domain\Bank\Values\BankCountryId;
use App\Domain\Bank\Values\BankDescription;
use App\Domain\Bank\Values\BankId;
use App\Domain\Bank\Values\BankItemId;
use App\Domain\Bank\Values\BankName;
use App\Domain\Bank\Values\BankSettings;
use App\Http\Requests\Bank\BankToggleRequest;

final class BankToggleUseCase
{
    private $bankContract;

    public function __construct(BankContract $bankContract)
    {
        $this->bankContract = $bankContract;
    }

    public function __invoke(BankToggleRequest $request, BankId $bankId): Bank
    {
        return $this->bankContract->toggle(
            new Bank(
                new BankId($bankId->value()),
                new BankItemId($request->integer('item_id')),
                new BankName($request->input('name')),
                new BankDescription($request->input('description')),
                new BankSettings((object) []),
                new BankCountryId($request->integer('country_id'))
            ),
            $bankId
        );
    }
}
