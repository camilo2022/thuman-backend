<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\User;

use App\Application\UserUseCase\UserToggleUseCase;
use App\Domain\User\Values\UserId;
use App\Http\Requests\User\UserToggleRequest;
use App\Http\Resources\User\UserResource;
use App\Infrastructure\Repositories\UserRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

final class UserToggleController
{
    use ApiResponser;
    use ApiMessage;
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(UserToggleRequest $request, int $userId)
    {
        Gate::authorize('toggleUser', $userId);

        try {
            $userId = new UserId($userId ?? null);
            $userToggleUseCase = new UserToggleUseCase($this->userRepository);
            $user = new UserResource($userToggleUseCase->__invoke($request, $userId));
            return $this->successResponse(
                $user,
                'El permiso fue asignado/removido al usuario exitosamente.',
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
