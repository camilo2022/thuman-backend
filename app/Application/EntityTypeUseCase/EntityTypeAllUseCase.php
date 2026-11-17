<?php

declare(strict_types=1);

namespace App\Application\EntityTypeUseCase;

use App\Domain\EntityType\Contracts\EntityTypeContract;
use App\Http\Requests\EntityType\EntityTypeAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class EntityTypeAllUseCase
{
    private $entityTypeContract;

    public function __construct(EntityTypeContract $entityTypeContract)
    {
        $this->entityTypeContract = $entityTypeContract;
    }

    public function __invoke(EntityTypeAllRequest $request): LengthAwarePaginator
    {
        return $this->entityTypeContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $request);
    }
}
