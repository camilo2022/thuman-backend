<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\HiringReason;

use App\Application\HiringReasonUseCase\HiringReasonFindUseCase;
use App\Domain\HiringReason\Values\HiringReasonId;
use App\Http\Resources\HiringReason\HiringReasonResource;
use App\Infrastructure\Repositories\HiringReasonRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class HiringReasonFindController
{
    use ApiResponser;
    use ApiMessage;
    private $hiringReasonRepository;

    public function __construct(HiringReasonRepository $hiringReasonRepository)
    {
        $this->hiringReasonRepository = $hiringReasonRepository;
    }

    public function __invoke(int $hiringReasonId)
    {
        try {
            $hiringReasonId = new HiringReasonId($hiringReasonId ?? null);
            $hiringReasonFindUseCase = new HiringReasonFindUseCase($this->hiringReasonRepository);
            $hiringReason = new HiringReasonResource($hiringReasonFindUseCase->__invoke($hiringReasonId));
            return $this->successResponse(
                $hiringReason,
                'El motivo solicitud de personal fue encontrada exitosamente.',
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
