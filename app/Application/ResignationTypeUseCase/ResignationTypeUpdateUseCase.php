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
use App\Http\Requests\ResignationType\ResignationTypeUpdateRequest;

final class ResignationTypeUpdateUseCase
{
    private $resignationTypeContract;

    public function __construct(ResignationTypeContract $resignationTypeContract)
    {
        $this->resignationTypeContract = $resignationTypeContract;
    }

    public function __invoke(ResignationTypeUpdateRequest $request, ResignationTypeId $resignationTypeId): ResignationType
    {
        return $this->resignationTypeContract->update(
            new ResignationType(
                new ResignationTypeId($resignationTypeId->value()),
                new ResignationTypeItemId($request->integer('item_id')),
                new ResignationTypeName($request->input('name')),
                new ResignationTypeDescription($request->input('description')),
                new ResignationTypeSettings((object) $request->input('settings'))
            ),
            $resignationTypeId
        );
    }
}
