<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Induction;

use App\Application\InductionUseCase\InductionAllUseCase;
use App\Http\Requests\Induction\InductionAllRequest;
use App\Http\Resources\Induction\InductionCollection;
use App\Infrastructure\Repositories\InductionRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class InductionAllController
{
    use ApiResponser;
    use ApiMessage;
    private $inductionRepository;

    public function __construct(InductionRepository $inductionRepository)
    {
        $this->inductionRepository = $inductionRepository;
    }

    public function __invoke(InductionAllRequest $request)
    {
        try {
            $inductionAllUseCase = new InductionAllUseCase($this->inductionRepository);
            $inductions = new InductionCollection($inductionAllUseCase->__invoke($request));
            return $this->successResponse(
                $inductions,
                'Las inducciones consultados exitosamente.',
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
