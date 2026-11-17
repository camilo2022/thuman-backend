<?php

declare(strict_types=1);

namespace App\Application\FiscalDocumentUseCase;

use App\Domain\FiscalDocument\Contracts\FiscalDocumentContract;
use App\Domain\FiscalDocument\FiscalDocument;
use App\Domain\FiscalDocument\Values\FiscalDocumentDescription;
use App\Domain\FiscalDocument\Values\FiscalDocumentId;
use App\Domain\FiscalDocument\Values\FiscalDocumentItemId;
use App\Domain\FiscalDocument\Values\FiscalDocumentName;
use App\Domain\FiscalDocument\Values\FiscalDocumentSettings;
use App\Http\Requests\FiscalDocument\FiscalDocumentUpdateRequest;

final class FiscalDocumentUpdateUseCase
{
    private $fiscalDocumentContract;

    public function __construct(FiscalDocumentContract $fiscalDocumentContract)
    {
        $this->fiscalDocumentContract = $fiscalDocumentContract;
    }

    public function __invoke(FiscalDocumentUpdateRequest $request, FiscalDocumentId $fiscalDocumentId): FiscalDocument
    {
        return $this->fiscalDocumentContract->update(
            new FiscalDocument(
                new FiscalDocumentId($fiscalDocumentId->value()),
                new FiscalDocumentItemId($request->integer('item_id')),
                new FiscalDocumentName($request->input('name')),
                new FiscalDocumentDescription($request->input('description')),
                new FiscalDocumentSettings((object) $request->input('settings'))
            ),
            $fiscalDocumentId
        );
    }
}
