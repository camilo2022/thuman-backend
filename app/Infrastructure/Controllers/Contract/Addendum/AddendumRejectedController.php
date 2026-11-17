<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Contract\Addendum;

use App\Application\ContractUseCase\AddendumUseCase\AddendumRejectedUseCase;
use App\Domain\Contract\Addendum\Values\AddendumId;
use App\Http\Requests\Contract\Addendum\AddendumRejectedRequest;
use App\Http\Resources\Contract\Addendum\AddendumResource;
use App\Infrastructure\Repositories\AddendumRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class AddendumRejectedController
{
    use ApiResponser;
    use ApiMessage;
    private $addendumRepository;

    public function __construct(AddendumRepository $addendumRepository)
    {
        $this->addendumRepository = $addendumRepository;
    }

    public function __invoke(AddendumRejectedRequest $request, int $addendumId)
    {
        try {
            $addendumId = new AddendumId($addendumId ?? null);
            $addendumRejectedUseCase = new AddendumRejectedUseCase($this->addendumRepository);
            $addendum = new AddendumResource($addendumRejectedUseCase->__invoke($request, $addendumId));
            return $this->successResponse(
                $addendum,
                'El otro sí fue actualizada exitosamente a: RECHAZADO.',
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
