<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Contract\Addendum;

use App\Application\ContractUseCase\AddendumUseCase\AddendumVerifyTokenUseCase;
use App\Domain\Contract\Addendum\Values\AddendumId;
use App\Http\Requests\Contract\Addendum\AddendumVerifyTokenRequest;
use App\Http\Resources\Contract\Addendum\AddendumResource;
use App\Infrastructure\Repositories\AddendumRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class AddendumVerifyTokenController
{
    use ApiResponser;
    use ApiMessage;
    private $addendumRepository;

    public function __construct(AddendumRepository $addendumRepository)
    {
        $this->addendumRepository = $addendumRepository;
    }

    public function __invoke(AddendumVerifyTokenRequest $request, int $addendumId)
    {
        try {
            $addendumId = new AddendumId($addendumId ?? null);
            $addendumVerifyTokenUseCase = new AddendumVerifyTokenUseCase($this->addendumRepository);
            $addendum = new AddendumResource($addendumVerifyTokenUseCase->__invoke($request, $addendumId));
            return $this->successResponse(
                $addendum,
                'Se ha verificado exitosamente código de acceso para firma del otro sí.',
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
