<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Induction;

use App\Application\InductionUseCase\InductionUpdateUseCase;
use App\Domain\Induction\Values\InductionId;
use App\Http\Requests\Induction\InductionUpdateRequest;
use App\Http\Resources\Induction\InductionResource;
use App\Infrastructure\Repositories\InductionRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class InductionUpdateController
{
    use ApiResponser;
    use ApiMessage;
    private $inductionRepository;

    public function __construct(InductionRepository $inductionRepository)
    {
        $this->inductionRepository = $inductionRepository;
    }

    public function __invoke(InductionUpdateRequest $request, int $inductionId)
    {
        try {
            $inductionId = new InductionId($inductionId ?? null);
            $inductionUpdateUseCase = new InductionUpdateUseCase($this->inductionRepository);
            $induction = new InductionResource($inductionUpdateUseCase->__invoke($request, $inductionId));
            return $this->successResponse(
                $induction,
                'La induccion fue actualizada exitosamente.',
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
