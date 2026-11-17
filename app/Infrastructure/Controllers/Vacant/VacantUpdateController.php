<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Vacant;

use App\Application\VacantUseCase\VacantUpdateUseCase;
use App\Domain\Vacant\Values\VacantId;
use App\Http\Requests\Vacant\VacantUpdateRequest;
use App\Http\Resources\Vacant\VacantResource;
use App\Infrastructure\Repositories\VacantRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class VacantUpdateController
{
    use ApiResponser;
    use ApiMessage;
    private $vacantRepository;

    public function __construct(VacantRepository $vacantRepository)
    {
        $this->vacantRepository = $vacantRepository;
    }

    public function __invoke(VacantUpdateRequest $request, int $vacantId)
    {
        try {
            $vacantId = new VacantId($vacantId ?? null);
            $vacantUpdateUseCase = new VacantUpdateUseCase($this->vacantRepository);
            $vacant = new VacantResource($vacantUpdateUseCase->__invoke($request, $vacantId));
            return $this->successResponse(
                $vacant,
                'La vacante fue actualizada exitosamente.',
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
