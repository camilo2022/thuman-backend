<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Vacant;

use App\Application\VacantUseCase\VacantPublishedUseCase;
use App\Domain\Vacant\Values\VacantId;
use App\Http\Requests\Vacant\VacantPublishedRequest;
use App\Http\Resources\Vacant\VacantResource;
use App\Infrastructure\Repositories\VacantRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class VacantPublishedController
{
    use ApiResponser;
    use ApiMessage;
    private $vacantRepository;

    public function __construct(VacantRepository $vacantRepository)
    {
        $this->vacantRepository = $vacantRepository;
    }

    public function __invoke(VacantPublishedRequest $request, int $vacantId)
    {
        try {
            $vacantId = new VacantId($vacantId ?? null);
            $vacantPublishedUseCase = new VacantPublishedUseCase($this->vacantRepository);
            $vacant = new VacantResource($vacantPublishedUseCase->__invoke($request, $vacantId));
            return $this->successResponse(
                $vacant,
                'La vacante fue actualizada exitosamente a: PUBLICADO.',
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
