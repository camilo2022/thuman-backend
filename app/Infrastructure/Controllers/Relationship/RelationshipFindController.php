<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Relationship;

use App\Application\RelationshipUseCase\RelationshipFindUseCase;
use App\Domain\Relationship\Values\RelationshipId;
use App\Http\Resources\Relationship\RelationshipResource;
use App\Infrastructure\Repositories\RelationshipRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class RelationshipFindController
{
    use ApiResponser;
    use ApiMessage;
    private $relationshipRepository;

    public function __construct(RelationshipRepository $relationshipRepository)
    {
        $this->relationshipRepository = $relationshipRepository;
    }

    public function __invoke(int $relationshipId)
    {
        try {
            $relationshipId = new RelationshipId($relationshipId ?? null);
            $relationshipFindUseCase = new RelationshipFindUseCase($this->relationshipRepository);
            $relationship = new RelationshipResource($relationshipFindUseCase->__invoke($relationshipId));
            return $this->successResponse(
                $relationship,
                'El parentesco fue encontrada exitosamente.',
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
