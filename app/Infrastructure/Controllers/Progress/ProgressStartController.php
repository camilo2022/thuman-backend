<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Progress;

use App\Application\ProgressUseCase\ProgressStartUseCase;
use App\Http\Requests\Progress\ProgressStartRequest;
use App\Http\Resources\Progress\ProgressResource;
use App\Infrastructure\Repositories\ProgressRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class ProgressStartController
{
    use ApiResponser;
    use ApiMessage;
    private $progressRepository;

    public function __construct(ProgressRepository $progressRepository)
    {
        $this->progressRepository = $progressRepository;
    }

    public function __invoke(ProgressStartRequest $request)
    {
        try {
            $progressStartUseCase = new ProgressStartUseCase($this->progressRepository);
            $progress = new ProgressResource($progressStartUseCase->__invoke($request));
            return $this->successResponse(
                $progress,
                'El paso inductivo ha comenzado.',
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
