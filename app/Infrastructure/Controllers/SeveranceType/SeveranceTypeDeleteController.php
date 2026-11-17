<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\SeveranceType;

use App\Application\SeveranceTypeUseCase\SeveranceTypeDeleteUseCase;
use App\Domain\SeveranceType\Values\SeveranceTypeId;
use App\Http\Resources\SeveranceType\SeveranceTypeResource;
use App\Infrastructure\Repositories\SeveranceTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class SeveranceTypeDeleteController
{
    use ApiResponser;
    use ApiMessage;
    private $severanceTypeRepository;

    public function __construct(SeveranceTypeRepository $severanceTypeRepository)
    {
        $this->severanceTypeRepository = $severanceTypeRepository;
    }

    public function __invoke(int $severanceTypeId)
    {
        try {
            $severanceTypeId = new SeveranceTypeId($severanceTypeId);
            $severanceTypeDeleteUseCase = new SeveranceTypeDeleteUseCase($this->severanceTypeRepository);
            $severanceType = new SeveranceTypeResource($severanceTypeDeleteUseCase->__invoke($severanceTypeId));
            return $this->successResponse(
                $severanceType,
                'El tipo de cesantías fue inactivada exitosamente.',
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
