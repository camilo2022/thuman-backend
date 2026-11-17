<?php

declare(strict_types=1);

namespace App\Application\BenefitUseCase;

use App\Domain\Benefit\Contracts\BenefitContract;
use App\Http\Requests\Benefit\BenefitAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class BenefitAllUseCase
{
    private $benefitContract;

    public function __construct(BenefitContract $benefitContract)
    {
        $this->benefitContract = $benefitContract;
    }

    public function __invoke(BenefitAllRequest $request): LengthAwarePaginator
    {
        return $this->benefitContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $request);
    }
}
