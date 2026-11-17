<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Resignation;

use App\Application\ResignationUseCase\ResignationAllUseCase;
use App\Http\Requests\Resignation\ResignationAllRequest;
use App\Http\Resources\Resignation\ResignationCollection;
use App\Infrastructure\Repositories\ResignationRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class ResignationAllController
{
    use ApiResponser;
    use ApiMessage;
    private $resignationRepository;

    public function __construct(ResignationRepository $resignationRepository)
    {
        $this->resignationRepository = $resignationRepository;
    }

    public function __invoke(ResignationAllRequest $request)
    {
        try {
            $resignationAllUseCase = new ResignationAllUseCase($this->resignationRepository);
            $resignations = new ResignationCollection($resignationAllUseCase->__invoke($request));
            return $this->successResponse(
                $resignations,
                'Las renuncias fueron consultadas exitosamente.',
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
