<?php

declare(strict_types=1);

namespace App\Application\PensionFundUseCase;

use App\Domain\PensionFund\Contracts\PensionFundContract;
use App\Http\Requests\PensionFund\PensionFundAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class PensionFundAllUseCase
{
    private $pensionFundContract;

    public function __construct(PensionFundContract $pensionFundContract)
    {
        $this->pensionFundContract = $pensionFundContract;
    }

    public function __invoke(PensionFundAllRequest $request): LengthAwarePaginator
    {
        return $this->pensionFundContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $request);
    }
}
