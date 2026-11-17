<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Progress;

use App\Application\ProgressUseCase\ProgressFinishUseCase;
use App\Domain\Progress\Values\ProgressId;
use App\Http\Requests\Progress\ProgressFinishRequest;
use App\Http\Resources\Progress\ProgressResource;
use App\Infrastructure\Repositories\ProgressRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class ProgressFinishController
{
    use ApiResponser;
    use ApiMessage;
    private $progressRepository;

    public function __construct(ProgressRepository $progressRepository)
    {
        $this->progressRepository = $progressRepository;
    }

    public function __invoke(ProgressFinishRequest $request, int $progressId)
    {
        try {
            $progressId = new ProgressId($progressId ?? null);
            $progressFinishUseCase = new ProgressFinishUseCase($this->progressRepository);
            $progress = new ProgressResource($progressFinishUseCase->__invoke($request, $progressId));
            return $this->successResponse(
                $progress,
                'El paso inductivo ha finalizado.',
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
