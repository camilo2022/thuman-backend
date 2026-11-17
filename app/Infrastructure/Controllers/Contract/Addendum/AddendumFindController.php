<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Contract\Addendum;

use App\Application\ContractUseCase\AddendumUseCase\AddendumFindUseCase;
use App\Domain\Contract\Addendum\Values\AddendumId;
use App\Http\Requests\Contract\Addendum\AddendumFindRequest;
use App\Http\Resources\Contract\Addendum\AddendumResource;
use App\Infrastructure\Repositories\AddendumRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class AddendumFindController
{
    use ApiResponser;
    use ApiMessage;
    private $addendumRepository;

    public function __construct(AddendumRepository $addendumRepository)
    {
        $this->addendumRepository = $addendumRepository;
    }

    public function __invoke(AddendumFindRequest $request, int|null $addendumId = null)
    {
        try {
            $addendumId = new AddendumId($addendumId);
            $addendumFindUseCase = new AddendumFindUseCase($this->addendumRepository);
            $addendum = new AddendumResource($addendumFindUseCase->__invoke($request, $addendumId));
            return $this->successResponse(
                $addendum,
                'El otro sí fue encontrado exitosamente.',
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
