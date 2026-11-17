<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\AddendumType;

use App\Application\AddendumTypeUseCase\AddendumTypeDeleteUseCase;
use App\Domain\AddendumType\Values\AddendumTypeId;
use App\Http\Resources\AddendumType\AddendumTypeResource;
use App\Infrastructure\Repositories\AddendumTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class AddendumTypeDeleteController
{
    use ApiResponser;
    use ApiMessage;
    private $addendumTypeRepository;

    public function __construct(AddendumTypeRepository $addendumTypeRepository)
    {
        $this->addendumTypeRepository = $addendumTypeRepository;
    }

    public function __invoke(int $addendumTypeId)
    {
        try {
            $addendumTypeId = new AddendumTypeId($addendumTypeId);
            $addendumTypeDeleteUseCase = new AddendumTypeDeleteUseCase($this->addendumTypeRepository);
            $addendumType = new AddendumTypeResource($addendumTypeDeleteUseCase->__invoke($addendumTypeId));
            return $this->successResponse(
                $addendumType,
                'El tipo de otrosí fue inactivado exitosamente.',
                204
            );
        } catch (HttpException $e) {
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('Exception'),
                    'error' => $e->getMessage(),
                    'status' => $e->getStatusCode()
                ],
                $e->getStatusCode()
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
