<?php

declare(strict_types=1);

namespace App\Application\ContractUseCase\AddendumUseCase;

use App\Domain\Contract\Addendum\Addendum;
use App\Domain\Contract\Addendum\Contracts\AddendumContract;
use App\Domain\Contract\Addendum\Values\AddendumId;
use App\Http\Requests\Contract\Addendum\AddendumNotifyTokenRequest;

final class AddendumNotifyTokenUseCase
{
    private $addendumContract;

    public function __construct(AddendumContract $addendumContract)
    {
        $this->addendumContract = $addendumContract;
    }

    public function __invoke(AddendumNotifyTokenRequest $request, AddendumId $addendumId): ?Addendum
    {
        return $this->addendumContract->notifyToken($request, $addendumId);
    }
}
