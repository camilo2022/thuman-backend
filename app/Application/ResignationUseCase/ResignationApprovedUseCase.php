<?php

declare(strict_types=1);

namespace App\Application\ResignationUseCase;

use App\Domain\Resignation\Contracts\ResignationContract;
use App\Domain\Resignation\Resignation;
use App\Domain\Resignation\Values\ResignationDate;
use App\Domain\Resignation\Values\ResignationResignationTypeId;
use App\Domain\Resignation\Values\ResignationId;
use App\Domain\Resignation\Values\ResignationPersonId;
use App\Domain\Resignation\Values\ResignationReason;
use App\Domain\Resignation\Values\ResignationStatus;
use App\Models\Resignation as ResignationModel;
use App\Http\Requests\Resignation\ResignationApprovedRequest;

final class ResignationApprovedUseCase
{
    private $resignationContract;

    public function __construct(ResignationContract $resignationContract)
    {
        $this->resignationContract = $resignationContract;
    }

    public function __invoke(ResignationApprovedRequest $request, ResignationId $resignationId): Resignation
    {
        return $this->resignationContract->update(
            new Resignation(
                new ResignationId($resignationId->value()),
                new ResignationPersonId($request->integer('person_id')),
                new ResignationResignationTypeId($request->integer('resignation_type_id')),
                new ResignationDate($request->input('date')),
                new ResignationReason($request->input('reason')),
                new ResignationStatus(ResignationModel::APPROVED)
            ),
            $resignationId
        );
    }
}
