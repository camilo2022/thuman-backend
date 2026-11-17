<?php

declare(strict_types=1);

namespace App\Domain\Contract\Contracts;

use App\Domain\Contract\Contract;
use App\Domain\Contract\Image;
use App\Domain\Contract\Values\ContractId;
use App\Http\Requests\Contract\ContractAllRequest;
use App\Http\Requests\Contract\ContractFindRequest;
use App\Http\Requests\Contract\ContractNotifyTokenRequest;
use App\Http\Requests\Contract\ContractVerifyTokenRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface ContractContract
{
    public function all(string|null $search, int|null $perPage, ContractAllRequest $request): LengthAwarePaginator;

    public function find(ContractFindRequest $request, ContractId $contractId): ?Contract;

    public function store(Contract $contract): Contract;

    public function update(Contract $contract, ContractId $contractId): Contract;

    public function image(Image $image): Image;

    public function notifyToken(ContractNotifyTokenRequest $request, ContractId $contractId): void;

    public function verifyToken(ContractVerifyTokenRequest $request, ContractId $contractId): ?Contract;

    public function preview(ContractId $contractId);
}
