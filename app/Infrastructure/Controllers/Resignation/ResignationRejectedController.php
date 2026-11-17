<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Resignation;

use App\Application\ResignationUseCase\ResignationRejectedUseCase;
use App\Domain\Resignation\Values\ResignationId;
use App\Http\Requests\Resignation\ResignationRejectedRequest;
use App\Http\Resources\Resignation\ResignationResource;
use App\Infrastructure\Repositories\ResignationRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class ResignationRejectedController
{
    use ApiResponser;
    use ApiMessage;
    private $resignationRepository;

    public function __construct(ResignationRepository $resignationRepository)
    {
        $this->resignationRepository = $resignationRepository;
    }

    public function __invoke(ResignationRejectedRequest $request, int $resignationId)
    {
        try {
            $resignationId = new ResignationId($resignationId ?? null);
            $resignationRejectedUseCase = new ResignationRejectedUseCase($this->resignationRepository);
            $resignation = new ResignationResource($resignationRejectedUseCase->__invoke($request, $resignationId));
            return $this->successResponse(
                $resignation,
                'La renuncia fue actualizada exitosamente a: RECHAZADO.',
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
