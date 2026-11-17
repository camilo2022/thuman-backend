<?php

declare(strict_types=1);

namespace App\Domain\FormationStatus\Contracts;

use App\Domain\FormationStatus\FormationStatus;
use App\Domain\FormationStatus\Values\FormationStatusId;
use Illuminate\Pagination\LengthAwarePaginator;

interface FormationStatusContract
{
    public function all(string|null $search, int|null $perPage): LengthAwarePaginator;

    public function find(FormationStatusId $formationStatusId): ?FormationStatus;
}
