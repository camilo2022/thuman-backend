<?php

declare(strict_types=1);

namespace App\Application\VacantUseCase\ApplicationUseCase;

use App\Domain\Vacant\Application\Application;
use App\Domain\Vacant\Application\Contracts\ApplicationContract;
use App\Domain\Vacant\Application\Values\ApplicationId;
use App\Http\Requests\Vacant\Application\ApplicationVerifyTokenRequest;

final class ApplicationVerifyTokenUseCase
{
    private $applicationContract;

    public function __construct(ApplicationContract $applicationContract)
    {
        $this->applicationContract = $applicationContract;
    }

    public function __invoke(ApplicationVerifyTokenRequest $request, ApplicationId $applicationId): ?Application
    {
        return $this->applicationContract->verifyToken($request, $applicationId);
    }
}
