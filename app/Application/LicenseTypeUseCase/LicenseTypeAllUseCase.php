<?php

declare(strict_types=1);

namespace App\Application\LicenseTypeUseCase;

use App\Domain\LicenseType\Contracts\LicenseTypeContract;
use App\Http\Requests\LicenseType\LicenseTypeAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class LicenseTypeAllUseCase
{
    private $licenseTypeContract;

    public function __construct(LicenseTypeContract $licenseTypeContract)
    {
        $this->licenseTypeContract = $licenseTypeContract;
    }

    public function __invoke(LicenseTypeAllRequest $request): LengthAwarePaginator
    {
        return $this->licenseTypeContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $request);
    }
}
