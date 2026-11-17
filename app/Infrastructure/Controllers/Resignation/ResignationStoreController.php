<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Resignation;

use App\Application\ResignationUseCase\ResignationStoreUseCase;
use App\Http\Requests\Resignation\ResignationStoreRequest;
use App\Http\Resources\Resignation\ResignationResource;
use App\Infrastructure\Repositories\ResignationRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class ResignationStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $resignationRepository;

    public function __construct(ResignationRepository $resignationRepository)
    {
        $this->resignationRepository = $resignationRepository;
    }

    public function __invoke(ResignationStoreRequest $request)
    {
        try {
            $resignationStoreUseCase = new ResignationStoreUseCase($this->resignationRepository);
            $resignation = new ResignationResource($resignationStoreUseCase->__invoke($request));
            return $this->successResponse(
                $resignation,
                'La renuncia fue registrada exitosamente.',
                201
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
