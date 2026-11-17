<?php

declare(strict_types=1);

namespace App\Application\CertificateTypeUseCase;

use App\Domain\CertificateType\Contracts\CertificateTypeContract;
use App\Http\Requests\CertificateType\CertificateTypeAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class CertificateTypeAllUseCase
{
    private $certificateTypeContract;

    public function __construct(CertificateTypeContract $certificateTypeContract)
    {
        $this->certificateTypeContract = $certificateTypeContract;
    }

    public function __invoke(CertificateTypeAllRequest $request): LengthAwarePaginator
    {
        return $this->certificateTypeContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $request);
    }
}
