<?php

declare(strict_types=1);

namespace App\Application\ContractTypeUseCase;

use App\Domain\ContractType\Contracts\ContractTypeContract;
use App\Domain\ContractType\ContractType;
use App\Domain\ContractType\Values\ContractTypeDescription;
use App\Domain\ContractType\Values\ContractTypeFileSubtypesId;
use App\Domain\ContractType\Values\ContractTypeId;
use App\Domain\ContractType\Values\ContractTypeItemId;
use App\Domain\ContractType\Values\ContractTypeName;
use App\Domain\ContractType\Values\ContractTypeSettings;
use App\Http\Requests\ContractType\ContractTypeStoreRequest;

final class ContractTypeStoreUseCase
{
    private $contractTypeContract;

    public function __construct(ContractTypeContract $contractTypeContract)
    {
        $this->contractTypeContract = $contractTypeContract;
    }

    public function __invoke(ContractTypeStoreRequest $request): ContractType
    {
        return $this->contractTypeContract->store(
            new ContractType(
                new ContractTypeId(null),
                new ContractTypeItemId($request->integer('item_id')),
                new ContractTypeName($request->input('name')),
                new ContractTypeDescription($request->input('description')),
                new ContractTypeSettings((object) $request->input('settings')),
                new ContractTypeFileSubtypesId($request->input('file_subtypes_id'))
            )
        );
    }
}
