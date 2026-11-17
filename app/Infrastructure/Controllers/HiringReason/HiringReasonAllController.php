<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\HiringReason;

use App\Application\HiringReasonUseCase\HiringReasonAllUseCase;
use App\Http\Requests\HiringReason\HiringReasonAllRequest;
use App\Http\Resources\HiringReason\HiringReasonCollection;
use App\Infrastructure\Repositories\HiringReasonRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class HiringReasonAllController
{
    use ApiResponser;
    use ApiMessage;
    private $hiringReasonRepository;

    public function __construct(HiringReasonRepository $hiringReasonRepository)
    {
        $this->hiringReasonRepository = $hiringReasonRepository;
    }

    public function __invoke(HiringReasonAllRequest $request)
    {
        try {
            $hiringReasonAllUseCase = new HiringReasonAllUseCase($this->hiringReasonRepository);
            $hiringReasons = new HiringReasonCollection($hiringReasonAllUseCase->__invoke($request));
            return $this->successResponse(
                $hiringReasons,
                'Los motivos solicitud de personal consultados exitosamente.',
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
