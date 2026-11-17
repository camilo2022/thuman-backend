<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Induction;

use App\Application\InductionUseCase\InductionFindUseCase;
use App\Domain\Induction\Values\InductionId;
use App\Http\Resources\Induction\InductionResource;
use App\Infrastructure\Repositories\InductionRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class InductionFindController
{
    use ApiResponser;
    use ApiMessage;
    private $inductionRepository;

    public function __construct(InductionRepository $inductionRepository)
    {
        $this->inductionRepository = $inductionRepository;
    }

    public function __invoke(int $inductionId)
    {
        try {
            $inductionId = new InductionId($inductionId ?? null);
            $inductionFindUseCase = new InductionFindUseCase($this->inductionRepository);
            $induction = new InductionResource($inductionFindUseCase->__invoke($inductionId));
            return $this->successResponse(
                $induction,
                'La induccion fue encontrada exitosamente.',
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
