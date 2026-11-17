<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Vacant;

use App\Application\VacantUseCase\VacantFindUseCase;
use App\Domain\Vacant\Values\VacantId;
use App\Http\Requests\Vacant\VacantFindRequest;
use App\Http\Resources\Vacant\VacantResource;
use App\Infrastructure\Repositories\VacantRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class VacantFindController
{
    use ApiResponser;
    use ApiMessage;
    private $vacantRepository;

    public function __construct(VacantRepository $vacantRepository)
    {
        $this->vacantRepository = $vacantRepository;
    }

    public function __invoke(VacantFindRequest $request, int|null $vacantId = null)
    {
        try {
            $vacantId = new VacantId($vacantId ?? null);
            $vacantFindUseCase = new VacantFindUseCase($this->vacantRepository);
            $vacant = new VacantResource($vacantFindUseCase->__invoke($request, $vacantId));
            return $this->successResponse(
                $vacant,
                'La vacante fue encontrada exitosamente.',
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
