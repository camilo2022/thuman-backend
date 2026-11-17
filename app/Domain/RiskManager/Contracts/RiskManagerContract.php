<?php

declare(strict_types=1);

namespace App\Domain\RiskManager\Contracts;

use App\Domain\RiskManager\RiskManager;
use App\Domain\RiskManager\Values\RiskManagerId;
use App\Http\Requests\RiskManager\RiskManagerAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface RiskManagerContract
{
    public function all(string|null $search, int|null $perPage, RiskManagerAllRequest $request): LengthAwarePaginator;

    public function find(RiskManagerId $riskManagerId): ?RiskManager;

    public function store(RiskManager $riskManager): RiskManager;

    public function update(RiskManager $riskManager, RiskManagerId $riskManagerId): RiskManager;

    public function toggle(RiskManager $riskManager, RiskManagerId $riskManagerId): RiskManager;

    public function delete(RiskManagerId $riskManagerId): RiskManager;

    public function restore(RiskManagerId $riskManagerId): RiskManager;
}
