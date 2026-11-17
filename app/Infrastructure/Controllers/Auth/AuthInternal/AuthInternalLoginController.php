<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Auth\AuthInternal;

use App\Application\AuthUseCase\AuthInternalUseCase\AuthInternalLoginUseCase;
use App\Http\Requests\Auth\AuthInternal\AuthInternalLoginRequest;
use App\Http\Resources\Auth\AuthInternal\AuthInternalResource;
use App\Infrastructure\Repositories\AuthInternalRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class AuthInternalLoginController
{
    use ApiResponser;
    use ApiMessage;
    private $authInternalRepository;

    public function __construct(AuthInternalRepository $authInternalRepository)
    {
        $this->authInternalRepository = $authInternalRepository;
    }

    public function __invoke(AuthInternalLoginRequest $request)
    {
        try {
            $authInternalLoginUseCase = new AuthInternalLoginUseCase($this->authInternalRepository);
            $authInternal = new AuthInternalResource($authInternalLoginUseCase->__invoke($request));
            return $this->successResponse(
                $authInternal,
                'Usuario autenticado exitosamente.',
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
