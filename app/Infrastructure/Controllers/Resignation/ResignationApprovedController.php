<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Resignation;

use App\Application\ResignationUseCase\ResignationApprovedUseCase;
use App\Domain\Resignation\Values\ResignationId;
use App\Http\Requests\Resignation\ResignationApprovedRequest;
use App\Http\Resources\Resignation\ResignationResource;
use App\Infrastructure\Repositories\ResignationRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class ResignationApprovedController
{
    use ApiResponser;
    use ApiMessage;
    private $resignationRepository;

    public function __construct(ResignationRepository $resignationRepository)
    {
        $this->resignationRepository = $resignationRepository;
    }

    public function __invoke(ResignationApprovedRequest $request, int $resignationId)
    {
        try {
            $resignationId = new ResignationId($resignationId ?? null);
            $resignationApprovedUseCase = new ResignationApprovedUseCase($this->resignationRepository);
            $resignation = new ResignationResource($resignationApprovedUseCase->__invoke($request, $resignationId));
            return $this->successResponse(
                $resignation,
                'La renuncia fue actualizada exitosamente a: APROBADO.',
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
