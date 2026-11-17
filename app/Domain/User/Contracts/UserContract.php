<?php

declare(strict_types=1);

namespace App\Domain\User\Contracts;

use App\Domain\User\User;
use App\Domain\User\Values\UserId;
use App\Http\Requests\User\UserFindRequest;
use App\Http\Requests\User\UserRecoveryEmailRequest;
use App\Http\Requests\User\UserRecoveryPhoneNumberRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserContract
{
    public function all(string|null $search, int|null $perPage): LengthAwarePaginator;

    public function find(UserFindRequest $request, UserId $userId): ?User;

    public function store(User $user): User;

    public function update(User $user, UserId $userId): User;

    public function toggle(User $user, UserId $userId): User;

    public function recoveryEmail(UserRecoveryEmailRequest $request): ?User;

    public function recoveryPhoneNumber(UserRecoveryPhoneNumberRequest $request): ?User;
}
