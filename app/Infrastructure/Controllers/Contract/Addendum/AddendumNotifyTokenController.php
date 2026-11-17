<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Contract\Addendum;

use App\Application\ContractUseCase\AddendumUseCase\AddendumNotifyTokenUseCase;
use App\Domain\Contract\Addendum\Values\AddendumId;
use App\Http\Requests\Contract\Addendum\AddendumNotifyTokenRequest;
use App\Infrastructure\Repositories\ContractRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class AddendumNotifyTokenController
{
    use ApiResponser;
    use ApiMessage;
    private $addendumRepository;

    public function __construct(AddendumRepository $addendumRepository)
    {
        $this->addendumRepository = $addendumRepository;
    }

    public function __invoke(AddendumNotifyTokenRequest $request, int|null $addendumId = null)
    {
        try {
            $addendumId = new AddendumId($addendumId);
            $addendumNotifyTokenUseCase = new AddendumNotifyTokenUseCase($this->addendumRepository);
            $addendumNotifyTokenUseCase->__invoke($request, $addendumId);
            return $this->successResponse(
                '',
                'Se ha enviado exitosamente el código de acceso para firmar el otro sí al correo electrónico asociado a la persona.',
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
