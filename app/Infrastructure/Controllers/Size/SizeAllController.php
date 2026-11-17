<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Size;

use App\Application\SizeUseCase\SizeAllUseCase;
use App\Http\Requests\Size\SizeAllRequest;
use App\Http\Resources\Size\SizeCollection;
use App\Infrastructure\Repositories\SizeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class SizeAllController
{
    use ApiResponser;
    use ApiMessage;
    private $sizeRepository;

    public function __construct(SizeRepository $sizeRepository)
    {
        $this->sizeRepository = $sizeRepository;
    }

    public function __invoke(SizeAllRequest $request)
    {
        try {
            $sizeAllUseCase = new SizeAllUseCase($this->sizeRepository);
            $sizes = new SizeCollection($sizeAllUseCase->__invoke($request));
            return $this->successResponse(
                $sizes,
                'Las tallas consultados exitosamente.',
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
