<?php

declare(strict_types=1);

namespace App\Domain\DocumentType\Contracts;

use App\Domain\DocumentType\DocumentType;
use App\Domain\DocumentType\Values\DocumentTypeId;
use App\Http\Requests\DocumentType\DocumentTypeAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface DocumentTypeContract
{
    public function all(string|null $search, int|null $perPage, DocumentTypeAllRequest $request): LengthAwarePaginator;

    public function find(DocumentTypeId $documentTypeId): ?DocumentType;

    public function store(DocumentType $documentType): DocumentType;

    public function update(DocumentType $documentType, DocumentTypeId $documentTypeId): DocumentType;

    public function delete(DocumentTypeId $documentTypeId): DocumentType;

    public function restore(DocumentTypeId $documentTypeId): DocumentType;
}
