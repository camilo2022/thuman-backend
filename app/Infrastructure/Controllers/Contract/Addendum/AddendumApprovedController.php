<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Contract\Addendum;

use App\Application\ContractUseCase\AddendumUseCase\AddendumApprovedUseCase;
use App\Domain\Contract\Addendum\Values\AddendumId;
use App\Http\Requests\Contract\Addendum\AddendumApprovedRequest;
use App\Http\Resources\Contract\Addendum\AddendumResource;
use App\Infrastructure\Repositories\AddendumRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class AddendumApprovedController
{
    use ApiResponser;
    use ApiMessage;
    private $addendumRepository;

    public function __construct(CAddendumRepository $addendumRepository)
    {
        $this->addendumRepository = $addendumRepository;
    }

    public function __invoke(AddendumApprovedRequest $request, int $addendumId)
    {
        try {
            $addendumId = new AddendumId($addendumId ?? null);
            $addendumApprovedUseCase = new AddendumApprovedUseCase($this->addendumRepository);
            $addendum = new AddendumResource($addendumApprovedUseCase->__invoke($request, $addendumId));
            return $this->successResponse(
                $addendum,
                'El otro sí fue actualizada exitosamente a: APROBADO.',
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
