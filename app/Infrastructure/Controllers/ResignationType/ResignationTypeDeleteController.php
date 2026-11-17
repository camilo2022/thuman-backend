<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\ResignationType;

use App\Application\ResignationTypeUseCase\ResignationTypeDeleteUseCase;
use App\Domain\ResignationType\Values\ResignationTypeId;
use App\Http\Resources\ResignationType\ResignationTypeResource;
use App\Infrastructure\Repositories\ResignationTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class ResignationTypeDeleteController
{
    use ApiResponser;
    use ApiMessage;
    private $resignationTypeRepository;

    public function __construct(ResignationTypeRepository $resignationTypeRepository)
    {
        $this->resignationTypeRepository = $resignationTypeRepository;
    }

    public function __invoke(int $resignationTypeId)
    {
        try {
            $resignationTypeId = new ResignationTypeId($resignationTypeId);
            $resignationTypeDeleteUseCase = new ResignationTypeDeleteUseCase($this->resignationTypeRepository);
            $resignationType = new ResignationTypeResource($resignationTypeDeleteUseCase->__invoke($resignationTypeId));
            return $this->successResponse(
                $resignationType,
                'El tipo de renuncia fue inactivada exitosamente.',
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
