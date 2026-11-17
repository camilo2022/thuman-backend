<?php

declare(strict_types=1);

namespace App\Domain\Contract\Addendum\Contracts;

use App\Domain\Contract\Addendum\Addendum;
use App\Domain\Contract\Addendum\Image;
use App\Domain\Contract\Addendum\Values\AddendumId;
use App\Domain\Contract\Values\ContractId;
use App\Http\Requests\Contract\Addendum\AddendumAllRequest;
use App\Http\Requests\Contract\Addendum\AddendumFindRequest;
use App\Http\Requests\Contract\Addendum\AddendumNotifyTokenRequest;
use App\Http\Requests\Contract\Addendum\AddendumVerifyTokenRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface AddendumContract
{
    public function all(string|null $search, int|null $perPage, AddendumAllRequest $request, ContractId $contractId): LengthAwarePaginator;

    public function find(AddendumFindRequest $request, AddendumId $addendumId): ?Addendum;

    public function store(Addendum $addendum): Addendum;

    public function update(Addendum $addendum, AddendumId $addendumId): Addendum;

    public function image(Image $image): Image;

    public function notifyToken(AddendumNotifyTokenRequest $request, AddendumId $addendumId): void;

    public function verifyToken(AddendumVerifyTokenRequest $request, AddendumId $addendumId): ?Addendum;
}
