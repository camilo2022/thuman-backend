<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Induction;

use App\Application\InductionUseCase\InductionDeleteUseCase;
use App\Domain\Induction\Values\InductionId;
use App\Http\Resources\Induction\InductionResource;
use App\Infrastructure\Repositories\InductionRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class InductionDeleteController
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
            $inductionId = new InductionId($inductionId);
            $inductionDeleteUseCase = new InductionDeleteUseCase($this->inductionRepository);
            $induction = new InductionResource($inductionDeleteUseCase->__invoke($inductionId));
            return $this->successResponse(
                $induction,
                'La induccion fue inactivada exitosamente.',
                204
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
