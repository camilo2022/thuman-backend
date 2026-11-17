<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Size;

use App\Application\SizeUseCase\SizeFindUseCase;
use App\Domain\Size\Values\SizeId;
use App\Http\Resources\Size\SizeResource;
use App\Infrastructure\Repositories\SizeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class SizeFindController
{
    use ApiResponser;
    use ApiMessage;
    private $sizeRepository;

    public function __construct(SizeRepository $sizeRepository)
    {
        $this->sizeRepository = $sizeRepository;
    }

    public function __invoke(int $sizeId)
    {
        try {
            $sizeId = new SizeId($sizeId ?? null);
            $sizeFindUseCase = new SizeFindUseCase($this->sizeRepository);
            $size = new SizeResource($sizeFindUseCase->__invoke($sizeId));
            return $this->successResponse(
                $size,
                'La talla fue encontrada exitosamente.',
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
