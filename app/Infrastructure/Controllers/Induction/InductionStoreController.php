<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Induction;

use App\Application\InductionUseCase\InductionStoreUseCase;
use App\Http\Requests\Induction\InductionStoreRequest;
use App\Http\Resources\Induction\InductionResource;
use App\Infrastructure\Repositories\InductionRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class InductionStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $inductionRepository;

    public function __construct(InductionRepository $inductionRepository)
    {
        $this->inductionRepository = $inductionRepository;
    }

    public function __invoke(InductionStoreRequest $request)
    {
        try {
            $inductionStoreUseCase = new InductionStoreUseCase($this->inductionRepository);
            $induction = new InductionResource($inductionStoreUseCase->__invoke($request));
            return $this->successResponse(
                $induction,
                'La induccion fue registrada exitosamente.',
                201
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
