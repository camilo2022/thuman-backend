<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Vacant;

use App\Application\VacantUseCase\VacantAllUseCase;
use App\Http\Requests\Vacant\VacantAllRequest;
use App\Http\Resources\Vacant\VacantCollection;
use App\Infrastructure\Repositories\VacantRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class VacantAllController
{
    use ApiResponser;
    use ApiMessage;
    private $vacantRepository;

    public function __construct(VacantRepository $vacantRepository)
    {
        $this->vacantRepository = $vacantRepository;
    }

    public function __invoke(VacantAllRequest $request)
    {
        try {
            $vacantAllUseCase = new VacantAllUseCase($this->vacantRepository);
            $vacants = new VacantCollection($vacantAllUseCase->__invoke($request));
            return $this->successResponse(
                $vacants,
                'Las vacantes fueron consultadas exitosamente.',
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
