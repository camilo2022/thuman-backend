<?php

declare(strict_types=1);

namespace App\Domain\ResignationType\Contracts;

use App\Domain\ResignationType\ResignationType;
use App\Domain\ResignationType\Values\ResignationTypeId;
use App\Http\Requests\ResignationType\ResignationTypeAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface ResignationTypeContract
{
    public function all(string|null $search, int|null $perPage, ResignationTypeAllRequest $request): LengthAwarePaginator;

    public function find(ResignationTypeId $resignationTypeId): ?ResignationType;

    public function store(ResignationType $resignationType): ResignationType;

    public function update(ResignationType $resignationType, ResignationTypeId $resignationTypeId): ResignationType;

    public function delete(ResignationTypeId $resignationTypeId): ResignationType;

    public function restore(ResignationTypeId $resignationTypeId): ResignationType;
}
