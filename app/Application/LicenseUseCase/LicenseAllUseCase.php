<?php

declare(strict_types=1);

namespace App\Application\LicenseUseCase;

use App\Domain\License\Contracts\LicenseContract;
use App\Http\Requests\License\LicenseAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class LicenseAllUseCase
{
    private $licenseContract;

    public function __construct(LicenseContract $licenseContract)
    {
        $this->licenseContract = $licenseContract;
    }

    public function __invoke(LicenseAllRequest $request): LengthAwarePaginator
    {
        return $this->licenseContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $request);
    }
}
