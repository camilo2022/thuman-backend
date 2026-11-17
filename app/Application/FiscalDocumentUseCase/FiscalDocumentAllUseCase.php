<?php

declare(strict_types=1);

namespace App\Application\FiscalDocumentUseCase;

use App\Domain\FiscalDocument\Contracts\FiscalDocumentContract;
use App\Http\Requests\FiscalDocument\FiscalDocumentAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class FiscalDocumentAllUseCase
{
    private $fiscalDocumentContract;

    public function __construct(FiscalDocumentContract $fiscalDocumentContract)
    {
        $this->fiscalDocumentContract = $fiscalDocumentContract;
    }

    public function __invoke(FiscalDocumentAllRequest $request): LengthAwarePaginator
    {
        return $this->fiscalDocumentContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $request);
    }
}
