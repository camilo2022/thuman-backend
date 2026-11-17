<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Contract\Addendum;

use App\Application\ContractUseCase\AddendumUseCase\AddendumAllUseCase;
use App\Domain\Contract\Values\ContractId;
use App\Http\Requests\Contract\Addendum\AddendumAllRequest;
use App\Http\Resources\Contract\Addendum\AddendumCollection;
use App\Infrastructure\Repositories\AddendumRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class AddendumAllController
{
    use ApiResponser;
    use ApiMessage;
    private $addendumRepository;

    public function __construct(AddendumRepository $addendumRepository)
    {
        $this->addendumRepository = $addendumRepository;
    }

    public function __invoke(AddendumAllRequest $request, int $contractId)
    {
        try {
            $contractId = new ContractId($contractId);
            $addendumAllUseCase = new AddendumAllUseCase($this->addendumRepository);
            $addendums = new AddendumCollection($addendumAllUseCase->__invoke($request, $contractId));
            return $this->successResponse(
                $addendums,
                'Los otros síes consultados exitosamente.',
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
