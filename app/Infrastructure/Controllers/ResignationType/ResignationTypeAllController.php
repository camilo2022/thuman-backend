<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\ResignationType;

use App\Application\ResignationTypeUseCase\ResignationTypeAllUseCase;
use App\Http\Requests\ResignationType\ResignationTypeAllRequest;
use App\Http\Resources\ResignationType\ResignationTypeCollection;
use App\Infrastructure\Repositories\ResignationTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class ResignationTypeAllController
{
    use ApiResponser;
    use ApiMessage;
    private $resignationTypeRepository;

    public function __construct(ResignationTypeRepository $resignationTypeRepository)
    {
        $this->resignationTypeRepository = $resignationTypeRepository;
    }

    public function __invoke(ResignationTypeAllRequest $request)
    {
        try {
            $resignationTypeAllUseCase = new ResignationTypeAllUseCase($this->resignationTypeRepository);
            $resignationTypes = new ResignationTypeCollection($resignationTypeAllUseCase->__invoke($request));
            return $this->successResponse(
                $resignationTypes,
                'Los tipos de renuncias consultados exitosamente.',
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
