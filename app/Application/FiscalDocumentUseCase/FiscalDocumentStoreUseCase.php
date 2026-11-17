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
use App\Http\Requests\FiscalDocument\FiscalDocumentStoreRequest;

final class FiscalDocumentStoreUseCase
{
    private $fiscalDocumentContract;

    public function __construct(FiscalDocumentContract $fiscalDocumentContract)
    {
        $this->fiscalDocumentContract = $fiscalDocumentContract;
    }

    public function __invoke(FiscalDocumentStoreRequest $request): FiscalDocument
    {
        return $this->fiscalDocumentContract->store(
            new FiscalDocument(
                new FiscalDocumentId(null),
                new FiscalDocumentItemId($request->integer('item_id')),
                new FiscalDocumentName($request->input('name')),
                new FiscalDocumentDescription($request->input('description')),
                new FiscalDocumentSettings((object) $request->input('settings'))
            )
        );
    }
}
