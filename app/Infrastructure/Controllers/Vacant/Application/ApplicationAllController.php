<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Vacant\Application;

use App\Application\VacantUseCase\ApplicationUseCase\ApplicationAllUseCase;
use App\Domain\Vacant\Values\VacantId;
use App\Http\Requests\Vacant\Application\ApplicationAllRequest;
use App\Http\Resources\Vacant\Application\ApplicationCollection;
use App\Infrastructure\Repositories\ApplicationRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class ApplicationAllController
{
    use ApiResponser;
    use ApiMessage;
    private $vacantRepository;

    public function __construct(ApplicationRepository $vacantRepository)
    {
        $this->vacantRepository = $vacantRepository;
    }

    public function __invoke(ApplicationAllRequest $request, int|null $vacantId = null)
    {
        try {
            $vacantId = new VacantId($vacantId ?? null);
            $vacantAllUseCase = new ApplicationAllUseCase($this->vacantRepository);
            $vacants = new ApplicationCollection($vacantAllUseCase->__invoke($request, $vacantId), $vacantId);
            return $this->successResponse(
                $vacants,
                'Las aplicaciones consultadas exitosamente.',
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
