<?php

declare(strict_types=1);

namespace App\Domain\FiscalDocument\Contracts;

use App\Domain\FiscalDocument\FiscalDocument;
use App\Domain\FiscalDocument\Values\FiscalDocumentId;
use App\Http\Requests\FiscalDocument\FiscalDocumentAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface FiscalDocumentContract
{
    public function all(string|null $search, int|null $perPage, FiscalDocumentAllRequest $request): LengthAwarePaginator;

    public function find(FiscalDocumentId $fiscalDocumentId): ?FiscalDocument;

    public function store(FiscalDocument $fiscalDocument): FiscalDocument;

    public function update(FiscalDocument $fiscalDocument, FiscalDocumentId $fiscalDocumentId): FiscalDocument;

    public function toggle(FiscalDocument $fiscalDocument, FiscalDocumentId $fiscalDocumentId): FiscalDocument;

    public function delete(FiscalDocumentId $fiscalDocumentId): FiscalDocument;

    public function restore(FiscalDocumentId $fiscalDocumentId): FiscalDocument;
}
