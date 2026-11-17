<?php

declare(strict_types=1);

namespace App\Application\DocumentTypeUseCase;

use App\Domain\DocumentType\Contracts\DocumentTypeContract;
use App\Http\Requests\DocumentType\DocumentTypeAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class DocumentTypeAllUseCase
{
    private $documentTypeContract;

    public function __construct(DocumentTypeContract $documentTypeContract)
    {
        $this->documentTypeContract = $documentTypeContract;
    }

    public function __invoke(DocumentTypeAllRequest $request): LengthAwarePaginator
    {
        return $this->documentTypeContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $request);
    }
}
