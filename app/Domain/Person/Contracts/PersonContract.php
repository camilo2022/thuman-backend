<?php

declare(strict_types=1);

namespace App\Domain\Person\Contracts;

use App\Domain\Person\File;
use App\Domain\Person\Person;
use App\Domain\Person\Values\PersonId;
use App\Http\Requests\Person\PersonAllRequest;
use App\Http\Requests\Person\PersonFindRequest;
use App\Http\Requests\Person\PersonVerifyEmailRequest;
use App\Http\Requests\Person\PersonVerifyPhoneNumberRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface PersonContract
{
    public function all(?string $search, ?int $perPage, PersonAllRequest $request): LengthAwarePaginator;

    public function find(PersonFindRequest $request, PersonId $personId): ?Person;

    public function store(Person $person): Person;

    public function update(Person $person, PersonId $personId): Person;

    public function toggle(Person $person, PersonId $personId): Person;

    public function delete(PersonId $personId): Person;

    public function restore(PersonId $personId): Person;

    public function file(File $file): File;

    public function notifyEmail(PersonId $personId): void;

    public function notifyPhoneNumber(PersonId $personId): void;

    public function verifyEmail(PersonVerifyEmailRequest $request, PersonId $personId): ?Person;

    public function verifyPhoneNumber(PersonVerifyPhoneNumberRequest $request, PersonId $personId): ?Person;
}
