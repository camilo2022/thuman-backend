<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Disability;

use App\Application\DisabilityUseCase\DisabilityAllUseCase;
use App\Http\Requests\Disability\DisabilityAllRequest;
use App\Http\Resources\Disability\DisabilityCollection;
use App\Infrastructure\Repositories\DisabilityRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class DisabilityAllController
{
    use ApiResponser;
    use ApiMessage;
    private $disabilityRepository;

    public function __construct(DisabilityRepository $disabilityRepository)
    {
        $this->disabilityRepository = $disabilityRepository;
    }

    public function __invoke(DisabilityAllRequest $request)
    {
        try {
            $disabilityAllUseCase = new DisabilityAllUseCase($this->disabilityRepository);
            $disabilities = new DisabilityCollection($disabilityAllUseCase->__invoke($request));
            return $this->successResponse(
                $disabilities,
                'Las reportes de incapacidades consultadas exitosamente.',
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
