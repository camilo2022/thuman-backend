<?php

declare(strict_types=1);

namespace App\Application\ResignationTypeUseCase;

use App\Domain\ResignationType\Contracts\ResignationTypeContract;
use App\Domain\ResignationType\ResignationType;
use App\Domain\ResignationType\Values\ResignationTypeDescription;
use App\Domain\ResignationType\Values\ResignationTypeId;
use App\Domain\ResignationType\Values\ResignationTypeItemId;
use App\Domain\ResignationType\Values\ResignationTypeName;
use App\Domain\ResignationType\Values\ResignationTypeSettings;
use App\Http\Requests\ResignationType\ResignationTypeStoreRequest;

final class ResignationTypeStoreUseCase
{
    private $resignationTypeContract;

    public function __construct(ResignationTypeContract $resignationTypeContract)
    {
        $this->resignationTypeContract = $resignationTypeContract;
    }

    public function __invoke(ResignationTypeStoreRequest $request): ResignationType
    {
        return $this->resignationTypeContract->store(
            new ResignationType(
                new ResignationTypeId(null),
                new ResignationTypeItemId($request->integer('item_id')),
                new ResignationTypeName($request->input('name')),
                new ResignationTypeDescription($request->input('description')),
                new ResignationTypeSettings((object) $request->input('settings'))
            )
        );
    }
}
