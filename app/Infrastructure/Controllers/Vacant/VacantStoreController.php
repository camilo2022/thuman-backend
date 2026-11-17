<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Vacant;

use App\Application\VacantUseCase\VacantStoreUseCase;
use App\Http\Requests\Vacant\VacantStoreRequest;
use App\Http\Resources\Vacant\VacantResource;
use App\Infrastructure\Repositories\VacantRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class VacantStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $vacantRepository;

    public function __construct(VacantRepository $vacantRepository)
    {
        $this->vacantRepository = $vacantRepository;
    }

    public function __invoke(VacantStoreRequest $request)
    {
        try {
            $vacantStoreUseCase = new VacantStoreUseCase($this->vacantRepository);
            $vacant = new VacantResource($vacantStoreUseCase->__invoke($request));
            return $this->successResponse(
                $vacant,
                'La vacante fue registrada exitosamente.',
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
