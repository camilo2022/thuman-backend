<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Resignation;

use App\Application\ResignationUseCase\ResignationFindUseCase;
use App\Domain\Resignation\Values\ResignationId;
use App\Http\Resources\Resignation\ResignationResource;
use App\Infrastructure\Repositories\ResignationRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class ResignationFindController
{
    use ApiResponser;
    use ApiMessage;
    private $resignationRepository;

    public function __construct(ResignationRepository $resignationRepository)
    {
        $this->resignationRepository = $resignationRepository;
    }

    public function __invoke(int $resignationId)
    {
        try {
            $resignationId = new ResignationId($resignationId ?? null);
            $resignationFindUseCase = new ResignationFindUseCase($this->resignationRepository);
            $resignation = new ResignationResource($resignationFindUseCase->__invoke($resignationId));
            return $this->successResponse(
                $resignation,
                'La renuncia fue encontrada exitosamente.',
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
