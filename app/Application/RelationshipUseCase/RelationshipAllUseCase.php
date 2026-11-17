<?php

declare(strict_types=1);

namespace App\Application\RelationshipUseCase;

use App\Domain\Relationship\Contracts\RelationshipContract;
use App\Http\Requests\Relationship\RelationshipAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class RelationshipAllUseCase
{
    private $relationshipContract;

    public function __construct(RelationshipContract $relationshipContract)
    {
        $this->relationshipContract = $relationshipContract;
    }

    public function __invoke(RelationshipAllRequest $request): LengthAwarePaginator
    {
        return $this->relationshipContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null);
    }
}
