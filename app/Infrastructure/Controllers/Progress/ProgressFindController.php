<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Progress;

use App\Application\ProgressUseCase\ProgressFindUseCase;
use App\Domain\Progress\Values\ProgressId;
use App\Http\Requests\Progress\ProgressFindRequest;
use App\Http\Resources\Progress\ProgressResource;
use App\Infrastructure\Repositories\ProgressRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class ProgressFindController
{
    use ApiResponser;
    use ApiMessage;
    private $progressRepository;

    public function __construct(ProgressRepository $progressRepository)
    {
        $this->progressRepository = $progressRepository;
    }

    public function __invoke(ProgressFindRequest $request, int|null $progressId = null)
    {
        try {
            $progressId = new ProgressId($progressId ?? null);
            $progressFindUseCase = new ProgressFindUseCase($this->progressRepository);
            $progress = new ProgressResource($progressFindUseCase->__invoke($request, $progressId));
            return $this->successResponse(
                $progress,
                'El progreso del paso inductivo fue encontrado exitosamente.',
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
