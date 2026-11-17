<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Relationship;

use App\Application\RelationshipUseCase\RelationshipAllUseCase;
use App\Http\Requests\Relationship\RelationshipAllRequest;
use App\Http\Resources\Relationship\RelationshipCollection;
use App\Infrastructure\Repositories\RelationshipRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class RelationshipAllController
{
    use ApiResponser;
    use ApiMessage;
    private $relationshipRepository;

    public function __construct(RelationshipRepository $relationshipRepository)
    {
        $this->relationshipRepository = $relationshipRepository;
    }

    public function __invoke(RelationshipAllRequest $request)
    {
        try {
            $relationshipAllUseCase = new RelationshipAllUseCase($this->relationshipRepository);
            $relationships = new RelationshipCollection($relationshipAllUseCase->__invoke($request));
            return $this->successResponse(
                $relationships,
                'Los parentescos consultados exitosamente.',
                200
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('Exception'),
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }
}
