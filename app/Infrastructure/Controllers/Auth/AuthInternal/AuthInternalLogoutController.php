<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Auth\AuthInternal;

use App\Application\AuthUseCase\AuthInternalUseCase\AuthInternalLogoutUseCase;
use App\Http\Requests\Auth\AuthInternal\AuthInternalLogoutRequest;
use App\Http\Resources\Auth\AuthInternal\AuthInternalResource;
use App\Infrastructure\Repositories\AuthInternalRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class AuthInternalLogoutController
{
    use ApiResponser;
    use ApiMessage;
    private $authInternalRepository;

    public function __construct(AuthInternalRepository $authInternalRepository)
    {
        $this->authInternalRepository = $authInternalRepository;
    }

    public function __invoke(AuthInternalLogoutRequest $request)
    {
        try {
            $authInternalLogoutUseCase = new AuthInternalLogoutUseCase($this->authInternalRepository);
            $authInternal = new AuthInternalResource($authInternalLogoutUseCase->__invoke($request));
            return $this->successResponse(
                $authInternal,
                'Cierre de sesion exitosamente.',
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
