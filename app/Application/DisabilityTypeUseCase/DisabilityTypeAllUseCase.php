<?php

declare(strict_types=1);

namespace App\Application\DisabilityTypeUseCase;

use App\Domain\DisabilityType\Contracts\DisabilityTypeContract;
use App\Http\Requests\DisabilityType\DisabilityTypeAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class DisabilityTypeAllUseCase
{
    private $disabilityTypeContract;

    public function __construct(DisabilityTypeContract $disabilityTypeContract)
    {
        $this->disabilityTypeContract = $disabilityTypeContract;
    }

    public function __invoke(DisabilityTypeAllRequest $request): LengthAwarePaginator
    {
        return $this->disabilityTypeContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $request);
    }
}
