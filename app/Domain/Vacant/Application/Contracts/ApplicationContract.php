<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\Contracts;

use App\Domain\Vacant\Application\Application;
use App\Domain\Vacant\Application\Values\ApplicationId;
use App\Domain\Vacant\Application\Video;
use App\Domain\Vacant\Values\VacantId;
use App\Http\Requests\Vacant\Application\ApplicationAllRequest;
use App\Http\Requests\Vacant\Application\ApplicationFindRequest;
use App\Http\Requests\Vacant\Application\ApplicationVerifyTokenRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface ApplicationContract
{
    public function all(string|null $search, int|null $perPage, ApplicationAllRequest $request, VacantId $vacantId): LengthAwarePaginator;

    public function find(ApplicationFindRequest $request, ApplicationId $applicationId): ?Application;

    public function store(Application $application): Application;

    public function update(Application $application, ApplicationId $applicationId): Application;

    public function video(Video $video): Video;

    public function notifyToken(ApplicationId $applicationId): void;

    public function verifyToken(ApplicationVerifyTokenRequest $request, ApplicationId $applicationId): ?Application;
}
